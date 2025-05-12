<?php

declare(strict_types=1); // Enable strict type checking

namespace App\Traits;

use App\Models\Booking;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Collection; // Import Collection

trait TimeSlotAvailabilityTrait
{
    /**
     * Get active time slots that are not booked for a specific date.
     * This logic is shared between BookingController and DashboardController.
     *
     * @param string $date The date (YYYY-MM-DD) to check availability for.
     * @return \Illuminate\Database\Eloquent\Collection A collection of TimeSlot models available on this date.
     */
    protected function getAvailableTimeSlotsForDate(string $date): Collection
    {
        // Get IDs of time slots already booked for this specific date.
        // A booking for this date occupies the slot. We filter out *completed* bookings
        // because a completed booking means the slot for that date/time is now free again.
        // Based on the requirement "when booked not to appear", we assume 'booked' means 'pending'.
        $bookedTimeSlotIds = Booking::where('date', $date)
                                   ->where('is_completed', false) // Only count pending bookings as occupying the slot
                                   ->pluck('time_slot_id');

        // Get all TimeSlot definitions that are marked 'active'
        // AND whose IDs are NOT in the list of booked (pending) slots for this *specific date*.
        return TimeSlot::where('active', true)
            ->whereNotIn('id', $bookedTimeSlotIds)
            ->orderBy('time')
            ->get();
    }
}
