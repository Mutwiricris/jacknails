<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with summary statistics
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'completed_bookings' => Booking::where('is_completed', true)->count(),
            'pending_bookings' => Booking::where('is_completed', false)->count(),
            'total_revenue' => Booking::where('is_completed', true)->sum('total_price'),
            'today_bookings' => Booking::whereDate('created_at', today())->count(),
            'today_revenue' => Booking::where('is_completed', true)
                                     ->whereDate('completed_at', today())
                                     ->sum('total_price'),
        ];
        
        // Get recent bookings
        $recentBookings = Booking::orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        
        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
    
    /**
     * Show all bookings with filters
     */
    public function bookings(Request $request)
    {
        $query = Booking::query();
        
        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('is_completed', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_completed', false);
            }
        }
        
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Get results with pagination
        $bookings = $query->orderBy('date', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(15)
                         ->withQueryString();
        
        return view('admin.bookings.index', compact('bookings'));
    }
    
    /**
     * Show booking details
     */
    public function showBooking($id)
    {
        $booking = Booking::findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }
    
    /**
     * Mark booking as completed
     */
    public function completeBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Mark as completed
        $booking->is_completed = true;
        $booking->completed_at = now();
        $booking->save();
        
        return redirect()->route('admin.booking.show', $booking->id)
                        ->with('success', 'Booking marked as completed!');
    }
    
    /**
     * Reactivate a time slot (make it available again)
     */
    public function reactivateTimeSlot(Request $request, $id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        $timeSlot->active = true;
        $timeSlot->save();
        
        return redirect()->back()->with('success', 'Time slot reactivated successfully!');
    }
    
    /**
     * Show time slot management page
     */
    public function timeSlots()
    {
        $timeSlots = TimeSlot::orderBy('time')->get();
        return view('admin.timeslots.index', compact('timeSlots'));
    }
    
    /**
     * Store a new time slot
     */
    public function storeTimeSlot(Request $request)
    {
        $validated = $request->validate([
            'time' => 'required|string|unique:time_slots,time',
        ]);
        
        TimeSlot::create([
            'time' => $validated['time'],
            'active' => true,
        ]);
        
        return redirect()->route('admin.timeslots')
                        ->with('success', 'Time slot created successfully!');
    }
    
    /**
     * Delete a time slot
     */
    public function deleteTimeSlot($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        
        // Check if the time slot has associated bookings
        if ($timeSlot->bookings()->exists()) {
            return redirect()->route('admin.timeslots')
                           ->with('error', 'Cannot delete time slot with associated bookings.');
        }
        
        $timeSlot->delete();
        
        return redirect()->route('admin.timeslots')
                        ->with('success', 'Time slot deleted successfully!');
    }
    
    /**
     * Show reports page
     */
    public function reports(Request $request)
    {
        $period = $request->period ?? 'month';
        
        // Determine date range based on period
        $startDate = null;
        $endDate = today();
        
        switch ($period) {
            case 'week':
                $startDate = today()->subDays(7);
                break;
            case 'month':
                $startDate = today()->subDays(30);
                break;
            case 'quarter':
                $startDate = today()->subMonths(3);
                break;
            case 'year':
                $startDate = today()->subYear();
                break;
            case 'custom':
                $startDate = $request->filled('start_date') 
                    ? Carbon::parse($request->start_date) 
                    : today()->subDays(30);
                $endDate = $request->filled('end_date') 
                    ? Carbon::parse($request->end_date) 
                    : today();
                break;
        }
        
        // Get completed bookings within the date range
        $completedBookings = Booking::where('is_completed', true)
                                   ->whereBetween('completed_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                                   ->get();
        
        // Calculate summary statistics
        $totalRevenue = $completedBookings->sum('total_price');
        $totalCount = $completedBookings->count();
        
        // Group by service
        $serviceStats = [];
        foreach ($completedBookings as $booking) {
            foreach ($booking->services as $service) {
                $serviceName = $service['name'];
                if (!isset($serviceStats[$serviceName])) {
                    $serviceStats[$serviceName] = [
                        'count' => 0,
                        'revenue' => 0,
                    ];
                }
                $serviceStats[$serviceName]['count']++;
                $serviceStats[$serviceName]['revenue'] += $service['price'];
            }
        }
        
        // Sort services by revenue
        uasort($serviceStats, function ($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });
        
        // Group by day for chart
        $dailyRevenue = $completedBookings->groupBy(function ($booking) {
            return $booking->completed_at->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('total_price');
        });
        
        return view('admin.reports', compact(
            'period',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalCount',
            'serviceStats',
            'dailyRevenue'
        ));
    }
}