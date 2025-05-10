<?php
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

class BookingController extends Controller
{
    /**
     * Display the initial service selection page.
     */
    public function book(Request $request)
    {
        return view('Booking');
    }

    /**
     * Process selected services and show the date/time selection page.
     */
    public function DateTime(Request $request)
    {
        // Validate that 'services' is present and is a string (or array, if your JS sends multiple in one field)
        // Based on your Booking.blade.php, it sends a single comma-separated string via a hidden input.
        $request->validate([
            'services' => 'required|string',
            // Add validation for 'date' if it's expected from the first page form
            'date' => 'nullable|date', // Assuming date is optional on the first step
        ]);

        $selected = $request->input('services');
        $serviceList = explode(', ', $selected); // Split the comma-separated string
        $servicesWithPrices = [];

        // Parse the "name|price" format
        foreach ($serviceList as $item) {
            $parts = explode('|', $item);
            if (count($parts) === 2) {
                [$name, $price] = $parts;
                // Ensure price is numeric before casting
                if (is_numeric($price)) {
                    $servicesWithPrices[] = [
                        'name' => trim($name), // Trim whitespace
                        'price' => (float) $price,
                    ];
                } else {
                     Log::warning("Skipping service with non-numeric price during parsing: " . $item);
                     // Optionally return an error to the user if this happens
                }
            } else {
                Log::warning("Skipping service item with incorrect format during parsing: " . $item);
                 // Optionally return an error
            }
        }

         // If no valid services were parsed, and the input wasn't empty, maybe flag an error?
        if (empty($servicesWithPrices) && !empty($selected)) {
             Log::error("Failed to parse any services from input string: " . $selected);
             // You might want to redirect back with an error here
             // throw ValidationException::withMessages(['services' => 'Could not parse the selected services. Please try again.']);
             // For now, we'll proceed with an empty services array
        }


        // Get selected date or use today
        // Ensure the date format is consistent
        $date = $request->input('date') ? Carbon::parse($request->input('date'))->toDateString() : now()->toDateString();

        // Fetch available time slots from database
        // You might want to filter time slots based on the selected date here too
        // $timeSlots = TimeSlot::where('active', true)
        //                      ->whereDoesntHave('bookings', function ($query) use ($date) {
        //                          $query->where('date', $date);
        //                      })
        //                      ->orderBy('time')
        //                      ->get();
        // For now, keeping the simple fetch:
        $timeSlots = TimeSlot::where('active', true)->orderBy('time')->get();


        // Pass the parsed array of services to the next view
        return view('book-timedate', compact('servicesWithPrices', 'timeSlots', 'date', 'selected'));
    }

    /**
     * Store the booking details.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'order_notes' => 'nullable|string|max:5000',
            'time_slot_id' => 'required|exists:time_slots,id',
            // Expecting 'services' to be an array of strings like "name|price" from book-timedate form
            'services' => 'required|array', // Ensure 'services' is an array
            'services.*' => 'string', // Ensure each item in the array is a string
            'date' => 'required|date',
        ]);

        // Find the selected time slot
        $timeSlot = TimeSlot::findOrFail($validated['time_slot_id']);

        // Check if the time slot is already booked (basic check, advanced requires checking date + time slot availability)
        // A more robust check would be in a service class or dedicated logic
        $existingBooking = Booking::where('date', $validated['date'])
                                  ->where('time_slot_id', $validated['time_slot_id'])
                                  ->first();

        if ($existingBooking) {
             // If a booking already exists for this date and time slot
             // Check if it's completed or still pending
             if ($existingBooking->is_completed || !$timeSlot->active) { // Also check time slot active state
                  return back()->withInput()->withErrors([
                    'time_slot_id' => 'Sorry, this time slot is no longer available. Please select another date or time.'
                  ]);
             }
             // If an uncompleted booking already exists, maybe prevent a duplicate? Or update it?
             // For now, assuming we prevent duplicates for the exact time slot
             // throw ValidationException::withMessages(['time_slot_id' => 'This time slot has already been booked.']);
        }

         // If time slot became inactive between page load and submission
         if (!$timeSlot->active) {
              return back()->withInput()->withErrors([
                'time_slot_id' => 'Sorry, this time slot has just become unavailable. Please select another time.'
              ]);
         }


        // Calculate total price and build the services array for saving
        $servicesToSave = []; // Use a different variable name for clarity
        $totalPrice = 0;

        // Process the array of "name|price" strings received from the form
        foreach ($validated['services'] as $serviceString) {
            $parts = explode('|', $serviceString);
             // Ensure the string has the expected format
            if (count($parts) === 2) {
                [$name, $price] = $parts;
                 // Ensure price is numeric before casting and adding
                if (is_numeric($price)) {
                     $price = (float) $price;
                    $servicesToSave[] = [
                        'name' => trim($name), // Trim whitespace
                        'price' => $price,
                    ];
                    $totalPrice += $price;
                } else {
                    Log::warning("Skipping service string with non-numeric price during store processing: " . $serviceString);
                    // Handle error: maybe reject the booking or log and continue?
                }
            } else {
                 Log::warning("Skipping service string with incorrect format during store processing: " . $serviceString);
                 // Handle error
            }
        }

         // Optional: Check if any valid services were parsed
         if (empty($servicesToSave) && !empty($validated['services'])) {
              Log::error("No valid services were parsed from the submitted services array.", ['submitted_services' => $validated['services']]);
              // Optionally return a validation error if no services could be parsed from a non-empty input array
              // throw ValidationException::withMessages(['services' => 'Failed to process selected services. Please try again.']);
              // If validation already required 'services' to be a non-empty array, this check might be redundant depending on exact validation rules.
              // For now, we allow saving with an empty services array if the front-end allowed it.
         }


        // Create the booking
        $booking = Booking::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'order_notes' => $validated['order_notes'] ?? null,
            'date' => $validated['date'],
            'time_slot_id' => $timeSlot->id,
            'services' => $servicesToSave, // Save the processed array
            'total_price' => $totalPrice,
            'is_completed' => false,
        ]);

        // Mark the time slot as booked (more robust availability management needed in a real app)
        // This simple approach only marks the *specific* TimeSlot model instance as inactive.
        // It doesn't prevent booking the same time slot for a different date, or if another user is booking concurrently.
        // A proper system would check/reserve the slot based on date and time.
        $timeSlot->active = false; // This might not be the right logic if TimeSlots are reusable daily.
                                  // Consider a separate 'availability' system or booking constraints.
        $timeSlot->save(); // Saving the time slot model

        // Get Admin Email
        $adminEmail = Config::get('mail.admin_email', env('ADMIN_EMAIL', 'mutwiric00@gmail.com'));
        if (empty($adminEmail)) {
            Log::error("Admin email is not configured. Cannot send booking notification.");
            // Consider notifying the user that the admin won't get an email, but the booking is saved.
        } else {
            // Send Email Notification
            try {
                // Prepare data for email
                $emailData = $validated;
                $emailData['time'] = $timeSlot->time; // Add the time string
                $emailData['total_price'] = $totalPrice;
                $emailData['services'] = $servicesToSave; // Send the parsed services to email

                Mail::to($adminEmail)->send(new NewBookingNotificationMail($emailData));

            } catch (\Exception $e) {
                // Log error
                Log::error("Booking notification email failed to send: " . $e->getMessage(), [
                    'exception' => $e,
                    'recipient' => $adminEmail,
                ]);
                 // The booking is saved, only the email failed.
            }
        }


        // Redirect with Success
        return redirect()->route('booking.confirmation')->with(
            'success',
            'Thank you! Your booking request has been received. A confirmation email has been sent.' // Updated message
        );

    }

    /**
     * Display the booking confirmation page.
     */
    public function confirmation()
    {
        // You might want to retrieve booking details from the session or pass the booking ID
        // to display confirmation details here.
        return view('booking.confirmation');
    }
}