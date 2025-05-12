<?php

declare(strict_types=1); // Enable strict type checking

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Mail\NewBookingNotificationMail;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException; // Import ValidationException
use Illuminate\View\View; // Import View
use Illuminate\Http\RedirectResponse; // Import RedirectResponse
use Exception; // Import base Exception class
use Illuminate\Database\Eloquent\Collection; // Import Collection
use Illuminate\Http\JsonResponse; // Correct Import for JsonResponse


// Include the shared trait for time slot availability logic
// Ensure this trait file exists at app/Traits/TimeSlotAvailabilityTrait.php
use App\Traits\TimeSlotAvailabilityTrait;


class BookingController extends Controller
{
    // Use the trait to share availability logic
    use TimeSlotAvailabilityTrait;

    /**
     * Display the initial service selection page.
     */
    public function book(Request $request): View
    {
        // Assuming 'Booking' is the view for the initial service selection page
        // where the user selects services and then redirects to selectDateTime.
        return view('Booking');
    }

    /**
     * Process selected services (from URL) and show the date/time selection page.
     * Receives selected services (as a "+"-separated string of "name|price")
     * and optionally a date.
     * Finds available time slots based on the selected date or the next available date
     * if the requested date is full or in the past.
     */
    public function DateTime(Request $request): View
    {
        // Validate input from the service selection page URL
        $request->validate([
            'services' => 'required|string|min:1', // Expecting a string like "name|price+name|price..."
            'date' => 'nullable|date', // The date parameter is optional for the initial load
        ]);

        $selectedServicesString = $request->input('services');

        $servicesForView = []; // Data structure suitable for displaying services in the view
        // servicesForFormInput is generated directly in the Blade template loop from servicesForView
        $processingErrors = []; // To collect parsing errors

        // --- Parse the service string from the URL ---
        // Assuming the format is "name|price+name|price+..."
        // Split the main string by the '+' separator (encoded as %2B in URL)
        $serviceItems = explode('+', $selectedServicesString);

        if (empty($serviceItems)) {
             $processingErrors[] = 'No service items found in the URL string.';
        } else {
            foreach ($serviceItems as $item) {
                // Decode URL entities (%2B becomes +, + becomes space etc.)
                $decodedItem = urldecode($item);
                $parts = explode('|', $decodedItem);

                // Check if item is in "name|price" format
                if (count($parts) === 2) {
                    [$name, $price] = $parts;
                    // Trim any leading/trailing whitespace
                    $trimmedName = trim($name);
                    $trimmedPrice = trim($price);

                    if (is_numeric($trimmedPrice)) {
                        $priceFloat = (float) $trimmedPrice;

                        // Add to view display format
                        $servicesForView[] = [
                            'name' => $trimmedName,
                            'price' => $priceFloat,
                        ];

                    } else {
                        Log::warning("BookingController@DateTime: Skipping service with non-numeric price during parsing: " . $decodedItem);
                        $processingErrors[] = "Could not process service '" . htmlspecialchars($trimmedName) . "' due to invalid price format.";
                    }
                } else {
                     // Handle cases where maybe only the name is passed, or format is wrong
                    Log::warning("BookingController@DateTime: Skipping service item with incorrect format during parsing: " . $decodedItem);
                    $processingErrors[] = "Could not process service item '" . htmlspecialchars($decodedItem) . "' due to incorrect format (expected name|price).";
                    // If services can come without prices, you would look up the price here
                    // Example: $serviceFromDb = ServiceModel::where('name', trim($decodedItem))->first();
                    // If found, add to $servicesForView with price from DB.
                }
            }
        }
        // --- End Service Parsing ---

        // If no valid services were parsed but the input string wasn't empty,
        // it indicates a format issue or missing prices if prices are expected.
         if (empty($servicesForView) && !empty($selectedServicesString)) {
             Log::error("BookingController@DateTime: Failed to parse any valid services from URL string.", ['input_string' => $selectedServicesString, 'parsing_errors' => $processingErrors]);
             // Throw a validation exception to show a clear error message to the user
             throw ValidationException::withMessages(['services' => 'Could not process the selected services due to an unexpected format. Please try selecting again.']);
         }

        // --- Date Handling and Availability Finding ---

        $requestedDateString = $request->input('date');
        // Start date for availability search: either the requested date, today, or the earliest future date if requested was past
        $searchStartDate = $requestedDateString ? Carbon::parse($requestedDateString)->startOfDay() : now()->startOfDay();
        $today = now()->startOfDay();

        // Ensure search starts from today or a future date
        if ($searchStartDate->isBefore($today)) {
             $searchStartDate = $today;
             // Optional: Add a message to the session if the date was adjusted
             // session()->flash('warning', 'You selected a past date. Displaying availability from today.');
        }

        $currentDate = $searchStartDate->copy(); // The date whose slots are currently being displayed

        // Fetch available time slots for the initial current date using the trait
        $availableTimeSlots = $this->getAvailableTimeSlotsForDate($currentDate->toDateString());

        // If no time slots are available on the current date, find the next available date using the trait
        if ($availableTimeSlots->isEmpty()) {
             // Start searching for the next available date *after* the current date
            $nextAvailableDateString = $this->findNextAvailableDate($currentDate->toDateString());

            if ($nextAvailableDateString) {
                // Update currentDate to the found next available date
                $currentDate = Carbon::parse($nextAvailableDateString);
                 // Fetch slots for the found date using the trait
                $availableTimeSlots = $this->getAvailableTimeSlotsForDate($currentDate->toDateString());
            } else {
                 // No available dates found within the search range
                 Log::warning("BookingController@DateTime: No available booking dates found starting from " . $searchStartDate->toDateString());
                 return view('book-timedate', [
                     'servicesWithPrices' => $servicesForView, // For displaying service names/prices
                     'timeSlots' => collect(), // Pass an empty collection
                     'date' => $currentDate->toDateString(), // Show the last date checked or the original requested date
                     'noAvailability' => true, // Flag for the view to show a "no slots" message
                     'processingErrors' => $processingErrors, // Pass parsing errors to the view
                 ]);
            }
        }

        // Pass data to the date/time selection view ('book-timedate')
        // $servicesWithPrices contains array of ['name' => ..., 'price' => ...] for display and hidden inputs
        return view('book-timedate', [
             'servicesWithPrices' => $servicesForView, // For displaying service names/prices and populating hidden inputs
             'timeSlots' => $availableTimeSlots, // Available slots for the displayed date
             'date' => $currentDate->toDateString(), // The specific date being displayed (YYYY-MM-DD)
             'noAvailability' => false,
             'processingErrors' => $processingErrors, // Pass parsing errors to the view
         ]);
    }

    // --- getAvailableTimeSlotsForDate and findNextAvailableDate are now in TimeSlotAvailabilityTrait ---
    // They are used via the `use TimeSlotAvailabilityTrait;` statement above.


    /**
     * Get available time slots for a specific date via AJAX (for customer booking page).
     * This method is called by the JavaScript date picker on the book-timedate view.
     * It reuses the shared availability logic from the trait.
     *
     * @param \Illuminate\Http\Request $request The incoming request with 'date'.
     * @return \Illuminate\Http\JsonResponse JSON response containing available time slots.
     */
    // FIX: Corrected the return type hint namespace
    public function getAvailableTimeSlots(Request $request): \Illuminate\Http\JsonResponse
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
     * Store the booking details from the form submission.
     * Receives validated form data from the book-timedate view.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation - expects 'services' as an array of "name|price" strings from hidden inputs
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'nullable|string|max:50',
            'order_notes' => 'nullable|string|max:5000',
            'time_slot_id' => 'required|exists:time_slots,id',
            // Expecting 'services' to be an array of strings like "name|price" from book-timedate form hidden inputs
            'services' => 'required|array|min:1', // Ensure 'services' is an array with at least one item
            'services.*' => 'string', // Ensure each item in the array is a string ("name|price")
            'date' => 'required|date|after_or_equal:today', // Validate date is today or in the future
             'agree_terms' => 'required|accepted', // Validate the terms agreement checkbox
        ]);

        // Find the selected time slot definition
        // Use findOrFail to ensure the slot exists in the DB before proceeding
        $timeSlot = TimeSlot::findOrFail($validated['time_slot_id']);

        // --- CRITICAL PER-DAY AVAILABILITY CHECK ---
        // Check if this specific date and time slot is already booked *at the moment of submission*.
        // Check against uncompleted bookings. This prevents race conditions.
        $isAlreadyBooked = Booking::where('date', $validated['date'])
            ->where('time_slot_id', $validated['time_slot_id'])
            ->where('is_completed', false) // Check only uncompleted bookings
            ->exists();

        if ($isAlreadyBooked) {
             Log::warning("BookingController@store: Attempted to book already taken slot.", [
                 'date' => $validated['date'],
                 'time_slot_id' => $validated['time_slot_id'],
                 'email' => $validated['email'], // Log email for context
             ]);
             // Throw a validation exception to show an error message next to the time slot field
             throw ValidationException::withMessages([
                'time_slot_id' => 'Sorry, the selected date and time slot has just been booked. Please choose another time or date.'
             ]);
        }

         // Also perform a final check if the time slot definition is still active
         // (less likely to change during submission but good practice)
         if (!$timeSlot->active) {
              Log::warning("BookingController@store: Attempted to book inactive time slot definition.", [
                  'time_slot_id' => $validated['time_slot_id'],
                  'email' => $validated['email'],
              ]);
              throw ValidationException::withMessages([
                'time_slot_id' => 'Sorry, this time slot is no longer available. Please select another.'
              ]);
         }
         // --- END AVAILABILITY CHECK ---


        // Calculate total price and build the services array for saving (JSON column)
        $servicesToSave = []; // Use a different variable name for clarity
        $totalPrice = 0.0; // Initialize as float
        $parsingErrors = []; // Collect errors during processing of submitted services

        // $validated['services'] is expected to be an array of strings like ["name1|price1", "name2|price2"]
        foreach ($validated['services'] as $serviceString) {
            $parts = explode('|', $serviceString);
            // Ensure the string has the expected format
            if (count($parts) === 2) {
                [$name, $price] = $parts;
                $trimmedName = trim($name);
                $trimmedPrice = trim($price);

                // Ensure price is numeric before casting and adding
                if (is_numeric($trimmedPrice)) {
                     $priceFloat = (float) $trimmedPrice;
                    $servicesToSave[] = [
                        'name' => $trimmedName, // Trim whitespace
                        'price' => $priceFloat,
                    ];
                    $totalPrice += $priceFloat;
                } else {
                    Log::warning("BookingController@store: Skipping submitted service string with non-numeric price: " . $serviceString);
                     $parsingErrors[] = "Could not process service '" . htmlspecialchars($trimmedName) . "' due to invalid price format during submission.";
                }
            } else {
                 Log::warning("BookingController@store: Skipping submitted service string with incorrect format: " . $serviceString);
                 $parsingErrors[] = "Could not process submitted service item '" . htmlspecialchars($serviceString) . "' due to incorrect format (expected name|price).";
            }
        }

         // If no valid services were saved but the input array wasn't empty, something went wrong.
         // This is a critical error as the booking would have no services.
         if (empty($servicesToSave) && !empty($validated['services'])) {
              Log::error("BookingController@store: No valid services could be parsed from the submitted services array.", [
                  'submitted_services' => $validated['services'],
                  'parsing_errors' => $parsingErrors,
                  'email' => $validated['email'],
              ]);
              // Throw a validation error as no valid services were attached to the booking
              throw ValidationException::withMessages(['services' => 'Failed to process the selected services during booking. Please try again.']);
         }


        // Create the booking
        try {
            $booking = Booking::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null, // Use null if phone is empty
                'order_notes' => $validated['order_notes'] ?? null, // Use null if notes are empty
                'date' => $validated['date'],
                'time_slot_id' => $timeSlot->id,
                'services' => $servicesToSave, // Save the processed array (ensure 'services' is cast to 'array' or 'json' in the Model)
                'total_price' => $totalPrice,
                'is_completed' => false, // Default status
            ]);

             Log::info("Booking created successfully.", ['booking_id' => $booking->id, 'email' => $booking->email]);

        } catch (Exception $e) {
            Log::error("BookingController@store: Failed to create booking: " . $e->getMessage(), [
                'exception' => $e,
                'validated_data' => $validated, // Log validated data for debugging
            ]);
             // Re-throw as ValidationException or redirect back with error
             throw ValidationException::withMessages(['general' => 'There was an error saving your booking. Please try again.']);
             // Or: return back()->withInput()->with('error', 'There was an error saving your booking. Please try again.');
        }


        // Get Admin Email and Send Notification
        $adminEmail = Config::get('mail.admin_email', env('ADMIN_EMAIL', 'mutwiric00@gmail.com')); // Make sure ADMIN_EMAIL is set in your .env
        if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            Log::error("BookingController@store: Admin email is not configured correctly or is invalid. Cannot send booking notification.");
            // The booking is saved, just the notification failed.
        } else {
            try {
                // Prepare data for email - only include necessary booking details
                $emailData = [
                    'first_name' => $booking->first_name,
                    'last_name' => $booking->last_name,
                    'email' => $booking->email,
                    'phone' => $booking->phone,
                    'order_notes' => $booking->order_notes,
                    'date' => Carbon::parse($booking->date)->format('Y-m-d'), // Format date nicely
                    'time' => Carbon::parse($timeSlot->time)->format('h:i A'), // Format time nicely
                    'services' => $booking->services, // Already in array format
                    'total_price' => $booking->total_price,
                    'booking_id' => $booking->id,
                ];

                Mail::to($adminEmail)->send(new NewBookingNotificationMail($emailData));
                Log::info("Booking notification email sent successfully for booking ID: " . $booking->id);

            } catch (Exception $e) { // Catching base Exception for mail issues
                // Log error
                Log::error("BookingController@store: Booking notification email failed to send to {$adminEmail}: " . $e->getMessage(), [
                    'exception' => $e,
                    'booking_id' => $booking->id ?? 'N/A',
                    'recipient' => $adminEmail,
                ]);
                 // The booking is saved, only the email failed. Consider queuing emails for resilience.
            }
        }


        // Redirect with Success
        return redirect()->route('booking.confirmation')->with(
            'success',
            'Thank you! Your booking request has been received. A confirmation email has been sent.'
        );
    }

    /**
     * Display the booking confirmation page.
     */
    public function confirmation(): View
    {
        // You can retrieve the success message flashed by the store method here
        // using session('success').
        return view('booking.confirmation');
    }

    // --- getAvailableTimeSlots method is included above for AJAX calls ---
}
