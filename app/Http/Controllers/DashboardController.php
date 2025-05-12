<?php

declare(strict_types=1); // Enable strict type checking

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View; // Import View
use Illuminate\Http\JsonResponse; // Import JsonResponse
use Illuminate\Http\RedirectResponse; // Import RedirectResponse
use Illuminate\Database\Eloquent\Collection; // Import Collection
use Illuminate\Validation\ValidationException; // Import ValidationException
use Illuminate\Support\Facades\Log; // Import Log

// Include the shared trait for time slot availability logic
use App\Traits\TimeSlotAvailabilityTrait;

class DashboardController extends Controller
{
    // Use the trait to share availability logic
    use TimeSlotAvailabilityTrait;

    /**
     * Display admin dashboard with summary statistics.
     */
    public function index(): View
    {
        // Get statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'completed_bookings' => Booking::where('is_completed', true)->count(),
            'pending_bookings' => Booking::where('is_completed', false)->count(),
            // Use sum() directly on the query builder for efficiency
            'total_revenue' => (float) Booking::where('is_completed', true)->sum('total_price'),
            'today_bookings' => Booking::whereDate('date', today())->count(), // Count bookings scheduled for today
            'today_revenue' => (float) Booking::where('is_completed', true)
                                     ->whereDate('completed_at', today()) // Revenue from bookings *completed* today
                                     ->sum('total_price'),
        ];

        // Get recent bookings
        $recentBookings = Booking::with('timeSlot') // Eager load timeSlot for display
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }

    /**
     * Get available time slots for a specific date via AJAX.
     * This method is typically called by a date picker on the admin side.
     * It reuses the shared availability logic.
     */
    public function getAvailableTimeSlots(Request $request): JsonResponse
    {
        // Validate the input date
        $request->validate([
            'date' => 'required|date_format:Y-m-d|after_or_equal:today'
        ]);

        $dateString = $request->input('date');

        // Use the shared trait method to get available time slots for the date
        $availableTimeSlots = $this->getAvailableTimeSlotsForDate($dateString);

        // Return available time slots as JSON
        return response()->json([
            'available_time_slots' => $availableTimeSlots,
            'date' => $dateString,
        ]);
    }

    /**
     * Show all bookings with filters.
     */
    public function bookings(Request $request): View
    {
        $query = Booking::query();

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('is_completed', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_completed', false);
            }
            // Note: Consider adding 'all' or handling cases where status is neither
        }

        if ($request->filled('date')) {
            // Ensure date is in the correct format before using whereDate
            try {
                 $date = Carbon::parse($request->date)->toDateString();
                 $query->whereDate('date', $date);
            } catch (\Exception $e) {
                 Log::warning("Invalid date format provided for booking filter: " . $request->date);
                 // Optionally redirect back with an error or ignore the filter
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            // Use lowercase search for case-insensitive matching (database dependent, but safer)
            $query->where(function($q) use ($search) {
                $searchTerm = strtolower($search);
                $q->whereRaw('LOWER(first_name) like ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(last_name) like ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(email) like ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(phone) like ?', ["%{$searchTerm}%"]); // Also search phone
            });
        }

        // Get results with pagination
        $bookings = $query->with('timeSlot') // Eager load timeSlot relationship
                         ->orderBy('date', 'desc')
                         ->orderBy('time_slot_id') // Order by time slot after date for better readability
                         ->orderBy('created_at', 'desc')
                         ->paginate(15)
                         ->withQueryString(); // Keep filters in pagination links

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show booking details.
     *
     * @param int $id The booking ID.
     */
    public function showBooking(int $id): View
    {
        // Use findOrFail to automatically return 404 if not found
        $booking = Booking::with('timeSlot')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Mark booking as completed.
     * IMPORTANT: This method *does not* reactivate the TimeSlot definition.
     * Availability is determined by the absence of a *pending* booking for that date/time.
     *
     * @param int $id The booking ID.
     */
    public function completeBooking(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        // Prevent completing a booking that is already completed
        if ($booking->is_completed) {
             return redirect()->route('admin.booking.show', $booking->id)
                            ->with('warning', 'Booking is already marked as completed.');
        }

        // Mark as completed
        $booking->is_completed = true;
        $booking->completed_at = now(); // Record completion timestamp
        $booking->save();

        // --- REMOVED INCORRECT LOGIC ---
        // Removed: TimeSlot::where('id', $booking->time_slot_id)->update(['active' => true]);
        // Completing a specific booking instance should NOT change the general 'active' status
        // of the TimeSlot definition. Availability for a date/time slot is determined by
        // checking the 'bookings' table for pending bookings for that specific date and time_slot_id.
        // Marking is_completed = true makes the slot available again for that date
        // because our availability query (in the trait) filters out completed bookings.
        // --- END REMOVED LOGIC ---


        return redirect()->route('admin.booking.show', $booking->id)
                        ->with('success', 'Booking marked as completed!');
    }

    /**
     * Mark booking as pending (e.g., if completion was accidental).
     *
     * @param int $id The booking ID.
     */
    public function markPending(int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        // Prevent marking pending if already pending
        if (!$booking->is_completed) {
             return redirect()->route('admin.booking.show', $booking->id)
                            ->with('warning', 'Booking is already pending.');
        }

        // Mark as pending
        $booking->is_completed = false;
        $booking->completed_at = null; // Clear completion timestamp
        $booking->save();

         // Note: This action makes the slot appear 'booked' again for that date/time
         // in the availability checks, as the availability query filters out completed bookings.

        return redirect()->route('admin.booking.show', $booking->id)
                        ->with('success', 'Booking marked as pending!');
    }


    /**
     * Reactivate a time slot definition (make it generally available in the system).
     * This is a manual admin action, separate from booking completion.
     *
     * @param int $id The TimeSlot ID.
     */
    public function reactivateTimeSlot(int $id): RedirectResponse
    {
        $timeSlot = TimeSlot::findOrFail($id);

        // Prevent reactivating if already active
        if ($timeSlot->active) {
             return redirect()->back()->with('warning', 'Time slot is already active.');
        }

        $timeSlot->active = true;
        $timeSlot->save();

        return redirect()->back()->with('success', 'Time slot reactivated successfully!');
    }

     /**
      * Deactivate a time slot definition (make it generally unavailable in the system).
      *
      * @param int $id The TimeSlot ID.
      */
     public function deactivateTimeSlot(int $id): RedirectResponse
     {
         $timeSlot = TimeSlot::findOrFail($id);

         // Prevent deactivating if already inactive
         if (!$timeSlot->active) {
              return redirect()->back()->with('warning', 'Time slot is already inactive.');
         }

         // Optional: Check for future pending bookings before deactivating
         $hasFuturePendingBookings = Booking::where('time_slot_id', $id)
             ->where('is_completed', false)
             ->where('date', '>=', today())
             ->exists();

         if ($hasFuturePendingBookings) {
              return redirect()->back()->with('error', 'Cannot deactivate time slot with future pending bookings.');
         }


         $timeSlot->active = false;
         $timeSlot->save();

         return redirect()->back()->with('success', 'Time slot deactivated successfully!');
     }


    /**
     * Show time slot management page with availability information for today.
     * This method now uses the shared availability logic.
     */
    public function timeSlots(): View
    {
        $todayString = today()->toDateString();

        // Get all time slot definitions
        $allTimeSlots = TimeSlot::orderBy('time')->get();

        // Get IDs of time slots booked for today using the shared logic
        // We get the *available* ones, then find which ones from the *all* list are NOT available.
        $availableTodayIds = $this->getAvailableTimeSlotsForDate($todayString)->pluck('id');

        // Prepare data for the view
        $timeSlots = $allTimeSlots->map(function ($timeSlot) use ($availableTodayIds) {
             $timeSlot->is_available_today = $availableTodayIds->contains($timeSlot->id);
             // Add a flag indicating if it's booked today (opposite of available today)
             $timeSlot->is_booked_today = !$timeSlot->is_available_today;
             return $timeSlot;
        });

        // The view now receives TimeSlot models with added 'is_available_today' and 'is_booked_today' properties

        return view('admin.timeslots.index', compact('timeSlots', 'todayString'));
    }

    /**
     * Store a new time slot with enhanced validation.
     */
    public function storeTimeSlot(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'time' => [
                'required',
                'string',
                'unique:time_slots,time', // Ensures the time string is unique globally
                'regex:/^([01]\d|2[0-3]):([0-5]\d)$/' // Strict HH:MM format validation
            ],
        ]);

        TimeSlot::create([
            'time' => $validated['time'],
            'active' => true, // New time slots are active by default
        ]);

        return redirect()->route('admin.timeslots')
                        ->with('success', 'Time slot created successfully!');
    }

    /**
     * Delete a time slot with more robust checking.
     * Prevents deletion if there are any future pending bookings associated with it.
     *
     * @param int $id The TimeSlot ID.
     */
    public function deleteTimeSlot(int $id): RedirectResponse
    {
        $timeSlot = TimeSlot::findOrFail($id);

        // Prevent deletion if there are any future pending bookings
        $hasFuturePendingBookings = Booking::where('time_slot_id', $id)
            ->where('is_completed', false) // Only check pending bookings
            ->where('date', '>=', today()->toDateString()) // Check for today or future dates
            ->exists();

        if ($hasFuturePendingBookings) {
            return redirect()->route('admin.timeslots')
                           ->with('error', 'Cannot delete time slot with active or future pending bookings.');
        }

        // Optional: Consider preventing deletion if there are *any* bookings (past or future, completed or pending)
        // $hasAnyBookings = Booking::where('time_slot_id', $id)->exists();
        // if ($hasAnyBookings) { ... error message ... }
        // This depends on whether you want to keep historical booking records linked to the time slot ID.

        $timeSlot->delete();

        return redirect()->route('admin.timeslots')
                        ->with('success', 'Time slot deleted successfully!');
    }

    /**
     * Show reports page.
     */

	 public function getCalendarEvents(Request $request): JsonResponse
	 {
		 // FullCalendar sends start and end parameters (ISO 8601 format)
		 $start = $request->get('start');
		 $end = $request->get('end');
 
		 // Fetch bookings within the date range FullCalendar is currently viewing
		 $bookings = Booking::with('timeSlot') // Eager load timeSlot
			 ->where('date', '>=', Carbon::parse($start)->toDateString())
			 ->where('date', '<=', Carbon::parse($end)->toDateString())
			 // Optional: Filter by completion status if you only want to show pending or completed
			 // ->where('is_completed', false) // Example: Only show pending bookings
			 ->get();
 
		 // Format bookings for FullCalendar events
		 $events = $bookings->map(function ($booking) {
			 // Combine date and time for the 'start' property
			 $startDateTime = Carbon::parse($booking->date . ' ' . optional($booking->timeSlot)->time);
 
			 // Determine event color based on status (optional)
			 $color = $booking->is_completed ? '#28a745' : '#ffc107'; // Green for completed, Yellow for pending
 
			 return [
				 'id' => $booking->id,
				 'title' => $booking->first_name . ' ' . $booking->last_name . ' (' . ($booking->is_completed ? 'Completed' : 'Pending') . ')', // Event title
				 'start' => $startDateTime->toIso8601String(), // Start date and time in ISO 8601 format
				 // 'end' => $endDateTime->toIso8601String(), // Optional: if bookings have an end time
				 'url' => route('admin.booking.show', $booking->id), // Link to the booking details page
				 'color' => $color, // Event color
				 // Add any other data you want accessible in eventDidMount or eventClick
				 // 'extendedProps' => [
				 //     'service_names' => is_array($booking->services) ? implode(', ', array_column($booking->services, 'name')) : 'N/A',
				 // ]
			 ];
		 });
 
		 return response()->json($events);
	 }
 


    public function reports(Request $request): View
    {
        // Default period to 'month' if not provided
        $period = $request->period ?? 'month';

        // Determine date range based on period
        $startDate = today()->startOfDay(); // Default start date
        $endDate = today()->endOfDay();   // Default end date

        switch ($period) {
            case 'week':
                $startDate = today()->subDays(6)->startOfDay(); // Last 7 days including today
                $endDate = today()->endOfDay();
                break;
            case 'month':
                $startDate = today()->subDays(29)->startOfDay(); // Last 30 days including today
                $endDate = today()->endOfDay();
                break;
            case 'quarter':
                $startDate = today()->subMonths(3)->startOfDay();
                $endDate = today()->endOfDay();
                break;
            case 'year':
                $startDate = today()->subYear()->startOfDay();
                $endDate = today()->endOfDay();
                break;
            case 'custom':
                // Validate custom dates
                $request->validate([
                    'start_date' => 'required|date_format:Y-m-d',
                    'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
                ]);
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                break;
             case 'all': // Added 'all' period
                 $startDate = null; // Query without start date constraint
                 $endDate = today()->endOfDay(); // Still end at today for completed_at
                 break;
            default:
                 // Handle invalid period, default to month
                 $period = 'month';
                 $startDate = today()->subDays(29)->startOfDay();
                 $endDate = today()->endOfDay();
                 break;
        }

        // Get completed bookings within the date range
        $query = Booking::where('is_completed', true);

        if ($startDate) {
             $query->where('completed_at', '>=', $startDate);
        }
        if ($endDate) {
             $query->where('completed_at', '<=', $endDate);
        }

        $completedBookings = $query->get();


        // Calculate summary statistics
        $totalRevenue = (float) $completedBookings->sum('total_price');
        $totalCount = $completedBookings->count();

        // Group by service for service stats
        $serviceStats = [];
        foreach ($completedBookings as $booking) {
             // Ensure 'services' is an array (it should be with casting in the model)
             if (is_array($booking->services)) {
                 foreach ($booking->services as $service) {
                    // Access service details safely
                    $serviceName = $service['name'] ?? 'Unknown Service';
                    $servicePrice = $service['price'] ?? 0.0; // Use float for price

                    if (!isset($serviceStats[$serviceName])) {
                        $serviceStats[$serviceName] = [
                            'count' => 0,
                            'revenue' => 0.0, // Use float
                        ];
                    }
                    $serviceStats[$serviceName]['count']++;
                    $serviceStats[$serviceName]['revenue'] += $servicePrice;
                }
             } else {
                 Log::warning("Booking ID {$booking->id} has non-array services data.", ['services_data' => $booking->services]);
             }
        }

        // Sort services by revenue (descending)
        uasort($serviceStats, function (array $a, array $b): int {
            // Use spaceship operator for comparison
            return $b['revenue'] <=> $a['revenue'];
        });

        // Group by day for chart (using completed_at)
        $dailyRevenue = $completedBookings->groupBy(function (Booking $booking): string {
            return $booking->completed_at->format('Y-m-d');
        })->map(function (Collection $group): float {
            return (float) $group->sum('total_price');
        })->sortKeys(); // Sort by date

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
