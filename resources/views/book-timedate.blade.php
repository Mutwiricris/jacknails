@extends('layouts.app')

@section('content')

	<section class="tp-checkout-area pt-200 pb-120 bg-gray-100 dark:bg-gray-800 min-h-screen">
		<div class="container mx-auto px-4">
			{{-- The entire booking form should wrap all inputs needed for the store method --}}
			<form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
				@csrf {{-- Include CSRF token for POST request --}}

				<div class="flex flex-wrap -mx-4"> {{-- Use flexbox for layout --}}

					{{-- Booking Details Column --}}
					<div class="w-full lg:w-7/12 px-4 mb-8 lg:mb-0">
						<div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
							<h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Booking Details</h3>

							{{-- Display Validation Errors --}}
							@if($errors->any())
								<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
									role="alert">
									<strong class="font-bold">Oops!</strong>
									<span class="block sm:inline">Please fix the following errors:</span>
									<ul class="mt-2 list-disc list-inside">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							{{-- Service Summary from Previous Page --}}
							<div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
								<h4 class="text-xl font-semibold mb-3 text-gray-800 dark:text-white">Selected Services:</h4>
								@if (!empty($servicesWithPrices))
									<ul class="list-disc pl-5 text-gray-700 dark:text-gray-300">
										@foreach ($servicesWithPrices as $service)
											<li>{{ $service['name'] }} - Ksh {{ number_format($service['price'], 2) }}</li>
										@endforeach
									</ul>
								@else
									<p class="text-gray-500 dark:text-gray-400">No services selected.</p>
								@endif
							</div>

							{{-- Date and Time Slot Selection --}}
							<div>
								<h4 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Select Date & Time</h4>

								<div class="flex flex-wrap -mx-2 mb-6">
									{{-- Date Picker --}}
									<div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
										<label for="myDatepicker"
											class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select
											Date <span class="text-red-500">*</span></label>
										{{-- Initialize with old date or the date passed from controller --}}
										<input type="text" name="date" id="myDatepicker" placeholder="Pick a date" class="w-full border rounded-md py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white
												   @error('date') border-red-500 @enderror"
											value="{{ old('date', $date ?? '') }}" required readonly> {{-- Added readonly
										--}}
										@error('date')
											<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
										@enderror
									</div>

									{{-- Time Slot Selector - This area will be updated by JavaScript --}}
									<div class="w-full md:w-1/2 px-2">
										<label
											class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select
											Time Slot <span class="text-red-500">*</span></label>
										<div id="timeSlotsContainer" class="grid grid-cols-2 gap-2">
											{{-- Time slots will be loaded here by JavaScript --}}
											{{-- INCLUDED TIME SLOT LOOP DIRECTLY --}}
											@forelse($timeSlots as $timeSlot)
												<div>
													<input type="radio" id="time-{{ $timeSlot->id }}"
														value="{{ $timeSlot->id }}" name="time_slot_id" class="peer hidden"
														required>
													<label for="time-{{ $timeSlot->id }}" {{-- Corrected label 'for' attribute
														--}}
														class="block text-center p-3 border rounded-md cursor-pointer text-sm transition duration-150 ease-in-out
																	   hover:bg-gray-100 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600
																	   dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 dark:peer-checked:bg-indigo-700 dark:peer-checked:text-white dark:peer-checked:border-indigo-700">
														{{ \Carbon\Carbon::parse($timeSlot->time)->format('h:i A') }}
													</label>
												</div>
											@empty
												<div
													class="col-span-2 text-center p-4 border rounded-md text-gray-600 dark:text-gray-400">
													No time slots available for this date.
												</div>
											@endforelse
										</div>
										{{-- Display error below time slots --}}
										@error('time_slot_id')
											<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
										@enderror
										{{-- Message if no availability was found at all --}}
										@if(isset($noAvailability) && $noAvailability)
											<div
												class="col-span-2 text-center p-4 border rounded-md bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200 mt-4">
												No time slots available on the selected date or in the near future. Please try a
												different date later.
											</div>
										@endif
									</div>
								</div>
							</div>

							{{-- Hidden Inputs to pass Service Data to the Store Method --}}
							{{-- THIS IS CRUCIAL FOR THE store METHOD TO RECEIVE THE SERVICES --}}
							{{-- Use $servicesForFormInput which is prepared in the controller --}}
							@if (!empty($servicesWithPrices)) {{-- Using servicesWithPrices as it's the data source --}}
								@foreach($servicesWithPrices as $service) {{-- Iterate over the service arrays --}}
									{{-- Correct Hidden Input: type="hidden", name="services[]", value="name|price" --}}
									{{-- Concatenate name and price with '|' --}}
									<input type="hidden" name="services[]" value="{{ $service['name'] }}|{{ $service['price'] }}">
								@endforeach
							@endif


							{{-- Personal Details --}}
							<div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
								<h4 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Your Personal Details
								</h4>

								<div class="flex flex-wrap -mx-2">
									<div class="w-full md:w-1/2 px-2 mb-4">
										<label for="first_name"
											class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First
											Name <span class="text-red-500">*</span></label>
										<input type="text" id="first_name" name="first_name" required placeholder="John"
											class="w-full border rounded-md py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white
												   @error('first_name') border-red-500 @enderror"
											value="{{ old('first_name') }}">
										@error('first_name')
											<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
										@enderror
									</div>
									<div class="w-full md:w-1/2 px-2 mb-4">
										<label for="last_name"
											class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last
											Name <span class="text-red-500">*</span></label>
										<input type="text" id="last_name" name="last_name" required placeholder="Smith"
											class="w-full border rounded-md py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white
												   @error('last_name') border-red-500 @enderror"
											value="{{ old('last_name') }}">
										@error('last_name')
											<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
										@enderror
									</div>

									<div class="w-full px-2 mb-4">
										<label for="email"
											class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your
											email <span class="text-red-500">*</span></label>
										<input type="email" id="email" name="email" required placeholder="example@gmail.com"
											class="w-full border rounded-md py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white
												   @error('email') border-red-500 @enderror" value="{{ old('email') }}">
										@error('email')
											<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
										@enderror
									</div>

									<div class="w-full px-2 mb-4">
										<label for="phone"
											class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your
											Phone No</label>
										<input type="text" id="phone" name="phone" placeholder="e.g., 0712345678" class="w-full border rounded-md py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white
												   @error('phone') border-red-500 @enderror" value="{{ old('phone') }}">
										@error('phone')
											<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
										@enderror
									</div>

									<div class="w-full px-2 mb-4">
										<label for="order_notes"
											class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order
											notes (optional)</label>
										<textarea id="order_notes" name="order_notes"
											placeholder="Notes about your order, e.g. special requirements."
											class="w-full border rounded-md py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white
													  @error('order_notes') border-red-500 @enderror">{{ old('order_notes') }}</textarea>
										@error('order_notes')
											<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

						</div> {{-- End of Booking Details Card --}}
					</div> {{-- End of col-lg-7 (Booking Details) --}}


					{{-- Order Summary Column --}}
					<div class="w-full lg:w-5/12 px-4">
						<div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
							<h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Your Order Summary</h3>

							<div class="border border-gray-200 dark:border-gray-700 rounded-md p-4">
								<div
									class="flex justify-between font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 mb-2 text-gray-800 dark:text-white">
									<h4>Service</h4>
									<h4>Price</h4>
								</div>

								{{-- Display the selected services and their prices --}}
								@php
									$displayTotal = 0;
								@endphp
								@if (!empty($servicesWithPrices))
									@foreach ($servicesWithPrices as $service)
										<div class="flex justify-between text-sm text-gray-700 dark:text-gray-300 py-1">
											<p>{{ $service['name'] }}</p>
											<span>Ksh {{ number_format($service['price'], 2) }}</span>
										</div>
										@php
											$displayTotal += $service['price'];
										@endphp
									@endforeach
								@else
									<div class="text-center text-gray-500 dark:text-gray-400 py-2">No services selected.</div>
								@endif

								{{-- Display Total --}}
								<div
									class="flex justify-between font-semibold border-t border-gray-200 dark:border-gray-700 pt-2 mt-2 text-gray-800 dark:text-white">
									<span>Total</span>
									<span>Ksh {{ number_format($displayTotal, 2) }}</span>
								</div>
							</div>

							{{-- Payment Info --}}
							<div class="mt-6 text-gray-600 dark:text-gray-400 text-sm">
								<p>Payment will be processed upon completion of service.</p>
							</div>

							{{-- Agreement Checkbox --}}
							<div class="mt-6">
								<div class="flex items-center">
									<input id="agree_terms" type="checkbox" name="agree_terms" value="1" required
										class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-indigo-600">
									<label for="agree_terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
										I have read and agree to the <a href="#"
											class="text-indigo-600 hover:underline dark:text-indigo-400">website terms and
											conditions</a>.
									</label>
								</div>
							</div>

							{{-- Submit Button --}}
							<div class="mt-6">
								<button type="submit" id="bookNowButton" disabled
									class="w-full block text-center bg-gray-400 text-white py-3 rounded-md opacity-50 cursor-not-allowed
											transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
									Book Now
								</button>
							</div>
						</div> {{-- End of Order Summary Card --}}
					</div> {{-- End of col-lg-5 (Order Summary / Place Order) --}}

				</div> {{-- End of flex-wrap row --}}
			</form> {{-- End of the main form --}}

			{{-- Success Message (Outside the form) --}}
			@if (session('success'))
				<div class="container mx-auto px-4 mt-6">
					<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
						<strong class="font-bold">Success!</strong>
						<span class="block sm:inline">{{ session('success') }}</span>
					</div>
				</div>
			@endif

		</div> {{-- End of container --}}
	</section>

	{{-- Include Pikaday CSS --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
	{{-- Include Moment.js (Pikaday dependency for better date handling) --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	{{-- Pikaday JavaScript --}}
	<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const dateInput = document.getElementById('myDatepicker');
			const timeSlotsContainer = document.getElementById('timeSlotsContainer');
			const bookNowButton = document.getElementById('bookNowButton');
			const termsCheckbox = document.getElementById('agree_terms'); // Use the correct ID

			// Initialize Pikaday
			const picker = new Pikaday({
				field: dateInput,
				format: 'YYYY-MM-DD', // Ensure format matches your database and controller expectation
				minDate: new Date(), // Prevent selecting past dates
				// Use Moment.js for more robust date handling
				parse: function (dateString) {
					return moment(dateString, 'YYYY-MM-DD').toDate();
				},
				toString: function (date, format) {
					return moment(date).format('YYYY-MM-DD');
				},
				// Set initial date if old input exists (for validation errors)
				defaultDate: dateInput.value ? moment(dateInput.value, 'YYYY-MM-DD').toDate() : new Date(),
				setDefaultDate: dateInput.value ? true : false,
				i18n: { // Optional: Customize language
					previousMonth: 'Previous Month',
					nextMonth: 'Next Month',
					months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
					weekdays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
					weekdaysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
				},
				// Event listener for date selection
				onSelect: function (date) {
					const selectedDate = moment(date).format('YYYY-MM-DD');
					console.log('Date selected:', selectedDate);
					fetchAvailableTimeSlots(selectedDate);
					// Clear selected time slot when date changes
					clearSelectedTimeSlot();
					// Update button state
					updateBookButtonState();
				}
			});

			// Function to fetch available time slots via AJAX
			function fetchAvailableTimeSlots(dateString) {
				// You need a route configured for this AJAX call in your web.php
				// Example: Route::get('/get-available-time-slots', [App\Http\Controllers\BookingController::class, 'getAvailableTimeSlots']);
				// Make sure this route is accessible and returns JSON.
				// Using the DashboardController's method for now, but you might want a dedicated public method.
				const fetchUrl = `/get-available-time-slots?date=${dateString}`; // Adjust URL as needed

				// Show a loading indicator (optional)
				timeSlotsContainer.innerHTML = '<div class="col-span-2 text-center p-4 text-gray-600 dark:text-gray-400">Loading time slots...</div>';

				fetch(fetchUrl, {
					headers: {
						'X-Requested-With': 'XMLHttpRequest', // Indicate AJAX request
						'Content-Type': 'application/json',
						'Accept': 'application/json',
					}
				})
					.then(response => {
						if (!response.ok) {
							// Handle HTTP errors
							console.error('Error fetching time slots:', response.statusText);
							timeSlotsContainer.innerHTML = '<div class="col-span-2 text-center p-4 text-red-600 dark:text-red-400">Error loading time slots. Please try again.</div>';
							throw new Error('Network response was not ok.');
						}
						return response.json();
					})
					.then(data => {
						console.log('Available time slots received:', data);
						updateTimeSlotsUI(data.available_time_slots);
					})
					.catch(error => {
						console.error('Fetch error:', error);
						timeSlotsContainer.innerHTML = '<div class="col-span-2 text-center p-4 text-red-600 dark:text-red-400">Failed to load time slots.</div>';
					});
			}

			// Function to update the UI with fetched time slots
			function updateTimeSlotsUI(timeSlots) {
				timeSlotsContainer.innerHTML = ''; // Clear current time slots

				if (timeSlots.length === 0) {
					timeSlotsContainer.innerHTML = '<div class="col-span-2 text-center p-4 border rounded-md text-gray-600 dark:text-gray-400">No time slots available for this date.</div>';
				} else {
					timeSlots.forEach(timeSlot => {
						const formattedTime = moment(timeSlot.time, 'HH:mm:ss').format('h:mm A'); // Format time

						// Generate the HTML for each time slot radio button and label
						const radioHtml = `
								<div>
									<input type="radio" id="time-${timeSlot.id}" value="${timeSlot.id}" name="time_slot_id"
										class="peer hidden" required>
									<label for="time-${timeSlot.id}"
										class="block text-center p-3 border rounded-md cursor-pointer text-sm transition duration-150 ease-in-out
											   hover:bg-gray-100 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600
											   dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 dark:peer-checked:bg-indigo-700 dark:peer-checked:text-white dark:peer-checked:border-indigo-700">
										${formattedTime}
									</label>
								</div>
							`;
						timeSlotsContainer.innerHTML += radioHtml;
					});

					// Add event listeners to the newly created radio buttons
					document.querySelectorAll('input[name="time_slot_id"]').forEach(radio => {
						radio.addEventListener('change', updateBookButtonState);
					});
				}
			}

			// Function to clear the selected time slot radio button
			function clearSelectedTimeSlot() {
				document.querySelectorAll('input[name="time_slot_id"]').forEach(radio => {
					radio.checked = false;
				});
			}

			// Function to update the state of the "Book Now" button
			function updateBookButtonState() {
				const isDateSelected = dateInput.value !== '';
				// Check if any time slot radio button within the container is checked
				const isTimeSlotSelected = timeSlotsContainer.querySelector('input[name="time_slot_id"]:checked') !== null;
				const isTermsAgreed = termsCheckbox ? termsCheckbox.checked : true; // Assume agreed if checkbox doesn't exist

				if (isDateSelected && isTimeSlotSelected && isTermsAgreed) {
					bookNowButton.disabled = false;
					bookNowButton.classList.remove("opacity-50", "cursor-not-allowed", "bg-gray-400");
					bookNowButton.classList.add("bg-indigo-600", "hover:bg-indigo-700");
				} else {
					bookNowButton.disabled = true;
					bookNowButton.classList.add("opacity-50", "cursor-not-allowed", "bg-gray-400");
					bookNowButton.classList.remove("bg-indigo-600", "hover:bg-indigo-700");
				}
			}

			// Add event listeners to update button state
			if (termsCheckbox) { termsCheckbox.addEventListener('change', updateBookButtonState); }

			// Initial load: Fetch time slots for the date initially displayed (either today or old input)
			// This handles cases where the page reloads due to validation errors
			if (dateInput.value) {
				fetchAvailableTimeSlots(dateInput.value);
			} else {
				// If no date is pre-selected (first load), initialize with today's date and fetch slots
				const today = moment().format('YYYY-MM-DD');
				dateInput.value = today; // Set the date input value
				fetchAvailableTimeSlots(today); // Fetch slots for today
			}


			// Initial state check on page load
			updateBookButtonState();

			// Add event listener to the timeSlotsContainer to update button state when radios inside change
			// This is important because the radios are added dynamically
			timeSlotsContainer.addEventListener('change', function (event) {
				if (event.target && event.target.matches('input[name="time_slot_id"]')) {
					updateBookButtonState();
				}
			});
		});
	</script>

@endsection