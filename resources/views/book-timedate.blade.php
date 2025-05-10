@extends('layouts.app')
@section('content')

	<section class="tp-checkout-area pt-200 pb-120">
		{{-- The entire booking form should wrap all inputs needed for the store method --}}
		<form action="{{ route('booking.store') }}" method="POST">
			@csrf {{-- Include CSRF token for POST request --}}

			<div class="container">
				<div class="row">
					<div class="col-xl-7 col-lg-7">
						<div class="tp-checkout-verify">
							<h4>Appointment Request</h4>

							{{-- Coupon Code Section (Optional / Commented Out) --}}
							{{-- <div class="tp-checkout-verify-item">
								<p class="tp-checkout-verify-reveal">Have a coupon? <button type="button" class="tp-checkout-coupon-form-reveal-btn">Click here to enter your code</button></p>
								<div id="tpCheckoutCouponForm" class="tp-return-customer">
									<div class="tp-return-customer-input">
										<label>Coupon Code :</label>
										<input type="text" placeholder="Coupon">
									</div>
								</div>
							</div> --}}

							{{-- Display Validation Errors --}}
							@if($errors->any())
								<div class="alert alert-danger mb-4">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
						</div>
					</div>

					<div class="col-lg-7">
						<div class="tp-checkout-bill-area">
							<h3 class="tp-checkout-bill-title">Booking Details</h3>

							{{-- Service Summary from Previous Page (Optional Display) --}}
							 {{-- You can display the selected services here for confirmation --}}
							 <div class="mb-4">
								 <h4 class="text-xl font-semibold mb-2">Selected Services:</h4>
								 @if (!empty($servicesWithPrices))
									 <ul class="list-disc pl-5">
										 @foreach ($servicesWithPrices as $service)
											 <li class="text-gray-700">{{ $service['name'] }} - Ksh {{ number_format($service['price'], 2) }}</li>
										 @endforeach
									 </ul>
								 @else
									 <p class="text-gray-500">No services selected.</p>
								 @endif
							 </div>


							<div class="tp-checkout-bill-form">
								<div class="tp-checkout-bill-inner">
									<div class="row">
										<div class="col-md-12 p-2">
											<div class="pt-5 border-t mb-6 border-gray-200 flex flex-col sm:flex-row sm:space-x-5 rtl:space-x-reverse">

												{{-- Date Picker --}}
												<div class="w-full sm:w-1/2 mb-4 sm:mb-0"> {{-- Added container and margin --}}
													<label for="myDatepicker" class="block text-sm font-medium text-gray-700 mb-1">Select Date <span class="text-red-500">*</span></label>
													<input type="text" name="date" placeholder="Pick a date" class="input w-full pika-single border rounded-md py-2 px-3 @error('date') border-red-500 @enderror" id="myDatepicker" required>
													@error('date')
														<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
													@enderror
												</div>


												{{-- Time Slot Selector --}}
												<div class="w-full sm:w-1/2"> {{-- Added container --}}
													 <label class="block text-sm font-medium text-gray-700 mb-1">Select Time Slot <span class="text-red-500">*</span></label>
													 <div class="grid grid-cols-2 gap-2">
														@forelse($timeSlots as $timeSlot)
															<div>
																{{-- Radio button for each time slot --}}
																<input type="radio" id="time-{{ $timeSlot->id }}" value="{{ $timeSlot->id }}" name="time_slot_id"
																	class="peer hidden @error('time_slot_id') is-invalid @enderror" {{ $timeSlot->active ? '' : 'disabled' }} required>
																<label for="time-{{ $timeSlot->id }}"
																	class="block text-center p-3 border rounded-md cursor-pointer text-sm transition duration-150 ease-in-out
																			{{ $timeSlot->active ? 'hover:bg-gray-100 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600' : 'bg-gray-100 text-gray-500 cursor-not-allowed' }}">
																	{{ \Carbon\Carbon::parse($timeSlot->time)->format('h:i A') }} {{-- Format time nicely --}}
																	{{ $timeSlot->active ? '' : '(Booked)' }}
																</label>
															</div>
														@empty
															<div class="col-span-2 text-center p-4 border rounded-md text-gray-600">
																No time slots available for selection.
															</div>
														@endforelse
													 </div>
													 {{-- Display error below time slots --}}
													 @error('time_slot_id')
														<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
													@enderror
												</div>
											</div>

											{{-- Hidden Inputs to pass Service Data to the Store Method --}}
											{{-- THIS IS THE CRUCIAL PART TO ENSURE SERVICES ARE SAVED --}}
											@if (!empty($servicesWithPrices))
												@foreach($servicesWithPrices as $service)
													{{-- Correct Hidden Input: type="hidden", name="services[]", value="name|price" --}}
													<input type="hidden" name="services[]" value="{{ $service['name']}}|{{ $service['price'] }}">
												@endforeach
											@else
												{{-- Optionally add a hidden input indicating no services if that's a valid state --}}
												{{-- Or ideally, the previous page ensures this is not empty --}}
											@endif


											{{-- Personal Details --}}
											<div class="mt-8 border-t pt-6">
												<h4 class="text-xl font-semibold mb-4">Please enter your personal Details</h4>

												<div class="row">
													<div class="col-md-6 mb-4">
														<div class="tp-checkout-input">
															<label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
															<input type="text" id="first_name" name="first_name" required placeholder="John" class="w-full border rounded-md py-2 px-3 @error('first_name') border-red-500 @enderror" value="{{ old('first_name') }}">
															@error('first_name')
																<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
															@enderror
														</div>
													</div>
													<div class="col-md-6 mb-4">
														<div class="tp-checkout-input">
															<label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
															<input type="text" id="last_name" name="last_name" required placeholder="Smith" class="w-full border rounded-md py-2 px-3 @error('last_name') border-red-500 @enderror" value="{{ old('last_name') }}">
															@error('last_name')
																<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
															@enderror
														</div>
													</div>

													<div class="col-md-12 mb-4">
														<div class="tp-checkout-input">
															<label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your email <span class="text-red-500">*</span></label>
															<input type="email" id="email" name="email" required placeholder="example@gmail.com" class="w-full border rounded-md py-2 px-3 @error('email') border-red-500 @enderror" value="{{ old('email') }}">
															 @error('email')
																<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
															@enderror
														</div>
													</div>

													<div class="col-md-12 mb-4">
														<div class="tp-checkout-input">
															<label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Your Phone No</label>
															<input type="text" id="phone" name="phone" placeholder="07...." class="w-full border rounded-md py-2 px-3 @error('phone') border-red-500 @enderror" value="{{ old('phone') }}"> {{-- Changed type to text for phone --}}
															 @error('phone')
																<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
															@enderror
														</div>
													</div>

													<div class="col-md-12 mb-4">
														<div class="tp-checkout-input">
															<label for="order_notes" class="block text-sm font-medium text-gray-700 mb-1">Order notes (optional)</label>
															{{-- FIX: Added name="order_notes" attribute --}}
															<textarea id="order_notes" name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery." class="w-full border rounded-md py-2 px-3 @error('order_notes') border-red-500 @enderror">{{ old('order_notes') }}</textarea>
															 @error('order_notes')
																<div class="text-red-500 text-sm mt-1">{{ $message }}</div>
															@enderror
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div> {{-- End of col-lg-7 (Booking Details) --}}


					<div class="col-lg-5"> {{-- Start of col-lg-5 (Order Summary / Place Order) --}}
						<div class="tp-checkout-place">
							<h3 class="tp-checkout-place-title">Your Order Summary</h3> {{-- Updated title --}}

							<div class="tp-order-info-list">
								<ul>
									<li class="tp-order-info-list-header">
										<h4>Service</h4>
										<h4>Price</h4> {{-- Changed from Total to Price for clarity --}}
									</li>

									{{-- Display the selected services and their prices --}}
									@if (!empty($servicesWithPrices))
										@foreach ($servicesWithPrices as $service)
											<li class="tp-order-info-list-desc flex justify-between py-1 border-b border-gray-100"> {{-- Added classes for styling --}}
												<p class="text-sm text-gray-700">{{ $service['name'] }}</p>
												<span class="text-sm font-medium text-gray-900">Ksh {{ number_format($service['price'], 2) }}</span>
											</li>
										@endforeach
									@else
										 <li class="tp-order-info-list-desc text-center text-gray-500 py-2">No services selected.</li>
									@endif


									{{-- Display Subtotal (Calculate total price in the controller and pass it) --}}
									{{-- If you calculate and pass total_price from DateTime method --}}
									{{-- <li class="tp-order-info-list-subtotal flex justify-between py-2 border-b border-gray-100">
										 <span>Subtotal</span>
										 <span>Ksh {{ number_format($totalPrice ?? 0, 2) }}</span>
									</li> --}}

									{{-- Display Total (You can use the same totalPrice here) --}}
									<li class="tp-order-info-list-total flex justify-between py-2">
										<span>Total</span>
										 {{-- Calculate total price for display in the view --}}
										@php
											$displayTotal = 0;
											if (!empty($servicesWithPrices)) {
												foreach ($servicesWithPrices as $service) {
													$displayTotal += $service['price'];
												}
											}
										@endphp
										<span>Ksh {{ number_format($displayTotal, 2) }}</span>
									</li>
								</ul>
							</div>

							{{-- Payment Info --}}
							<div class="tp-checkout-payment mt-4">
								<p class="text-sm text-gray-600 dark:text-gray-400">Payment will be processed upon completion of service.</p>
							</div>

							{{-- Agreement Checkbox --}}
							<div class="tp-checkout-agree mt-4">
								 <div class="tp-checkout-option">
									{{-- Ensure this checkbox has a name if needed for validation/submission --}}
									<input id="read_all_bottom" type="checkbox" name="agree_terms" value="1" required> {{-- Added name and value --}}
									<label for="read_all_bottom" class="ml-1 text-sm text-gray-600 dark:text-gray-400">I have read and agree to the <a href="#" class="text-indigo-600 hover:underline">website terms and conditions</a>.</label>
								 </div>
							 </div>

							{{-- Submit Button --}}
							<div class="tp-checkout-btn-wrapper mt-6">
								 {{-- The form's submit button --}}
								<button type="submit" class="tp-btn-cart sm w-100 block text-center bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">Book Now</button> {{-- Added basic Tailwind styles --}}
							</div>
						</div> {{-- End of tp-checkout-place (Order Summary) --}}
					</div> {{-- End of col-lg-5 (Order Summary / Place Order) --}}

				</div> {{-- End of row --}}
			</div> {{-- End of container --}}
		</form> {{-- End of the main form --}}

		{{-- Success Message (Outside the form) --}}
		@if (session('success'))
			<div class="alert alert-success mt-4">
				{{ session('success') }}
			</div>
		@endif
	</section>

	{{-- Include Pikaday CSS --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

	{{-- Pikaday JavaScript --}}
	<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
	<script>
		// Initialize Pikaday
		var picker = new Pikaday({
			field: document.getElementById('myDatepicker'),
			format: 'YYYY-MM-DD', // Ensure format matches your database and controller expectation
			minDate: new Date(), // Prevent selecting past dates
			// Add other options as needed (e.g., disable dates already booked)
			// disabledDates: [new Date(2023, 10, 25), new Date(2023, 10, 26)]
		});

		// Optional: Add event listener to datepicker to potentially filter time slots dynamically
		// picker.config({
		//     onSelect: function(date) {
		//         // You would typically make an AJAX request here to fetch time slots
		//         // for the selected date and update the time slot radio buttons.
		//         console.log('Date selected:', date);
		//         // Example: fetch(`/api/available-timeslots?date=${date.toISOString().slice(0,10)}`)
		//         // .then(response => response.json())
		//         // .then(timeSlots => updateTimeSlotsUI(timeSlots));
		//     }
		// });

		// You might need JavaScript to handle the state of the "Book Now" button
		// if it should depend on date, time slot, and agreement being selected/checked.
		// The JS from the previous page is not included here by default.
		// Example simple button state control (adjust as needed):
		const bookNowButton = document.querySelector('button[type="submit"]');
		const timeSlotRadios = document.querySelectorAll('input[name="time_slot_id"]');
		const dateInput = document.getElementById('myDatepicker');
		const termsCheckbox = document.getElementById('read_all_bottom'); // Use the correct ID

		function updateBookButtonState() {
			const isDateSelected = dateInput.value !== '';
			const isTimeSlotSelected = [...timeSlotRadios].some(radio => radio.checked);
			const isTermsAgreed = termsCheckbox.checked;

			if (isDateSelected && isTimeSlotSelected && isTermsAgreed) {
				bookNowButton.disabled = false;
				 bookNowButton.classList.remove("opacity-50", "cursor-not-allowed");
				 bookNowButton.classList.add("bg-indigo-600", "hover:bg-indigo-700"); // Add active styles
			} else {
				bookNowButton.disabled = true;
				 bookNowButton.classList.add("opacity-50", "cursor-not-allowed");
				 bookNowButton.classList.remove("bg-indigo-600", "hover:bg-indigo-700"); // Remove active styles
			}
		}

		// Add event listeners to update button state
		dateInput.addEventListener('change', updateBookButtonState);
		timeSlotRadios.forEach(radio => radio.addEventListener('change', updateBookButtonState));
		if(termsCheckbox) { termsCheckbox.addEventListener('change', updateBookButtonState); } // Check if exists


		// Initial state check on page load
		updateBookButtonState();


	</script>

@endsection