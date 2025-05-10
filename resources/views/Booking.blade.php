@extends('layouts.app')

@section('content')

	<section class="tp-checkout-area pt-200 pb-120">
		<div class="container">
			<div class="row">
				<div class="col-xl-7 col-lg-7">
					<div class="tp-checkout-verify">
						<h4>Appointment Request</h4>
						{{-- Your commented coupon code section --}}
					</div>
				</div>
				<div class="col-lg-7">
					<div class="tp-checkout-bill-area">
						<h3 class="tp-checkout-bill-title">Select Service(s) <span class="text-sm text-gray-500">(Select up
								to 2)</span></h3> {{-- Added service limit info --}}

						<div class="tp-checkout-bill-form">
							{{-- The form action points to booking.DateTime, but the submit button is within the order
							summary section. --}}
							{{-- It's more typical to wrap the entire service selection and summary in the form --}}
							{{-- However, based on your controller flow (select services -> go to date/time page),
							the current form structure is correct for sending selected services to booking.DateTime --}}
							<form action="{{ route('booking.DateTime') }}" method="GET">
								{{-- @csrf --}} {{-- No need for @csrf on a GET request, but harmless --}}

								<div id="services" class="grid grid-cols-1 md:grid-cols-2 gap-3"> {{-- Added some grid
									styling to checkboxes --}}
									{{-- Checkboxes will be injected here by JavaScript --}}
								</div>
								<input type="hidden" name="services" id="selected-services">

								{{-- The submit button is below the order summary --}}


						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="tp-checkout-place">
						<h3 class="tp-checkout-place-title">Your Order</h3>

						<div class="tp-order-info-list">
							<ul>
								<li class="tp-order-info-list-header">
									<h4>Service</h4>
									<h4>Total</h4>
								</li>

								{{-- This span will be populated by JS --}}
								<div id="order-summary">
									{{-- JS will insert <li> elements here --}}
										<p class="text-gray-500 text-center">No items selected</p> {{-- Initial message
										moved inside the div --}}
								</div>


								<li class="tp-order-info-list-subtotal">
									<span>Subtotal</span>
									<span id="subtotal">Ksh 0.00</span>
								</li>

								{{-- FIX: Changed duplicate ID 'subtotal' to 'total' --}}
								<li class="tp-order-info-list-total">
									<span>Total</span>
									<span id="total">Ksh 0.00</span> {{-- FIX: Corrected ID to 'total' --}}
								</li>
							</ul>
						</div>
						{{-- Payment section (commented out radio button) --}}
						<div class="tp-checkout-payment">
							<p class="text-sm text-gray-600 dark:text-gray-400">Payment will be processed upon completion of
								service.</p>
						</div>

						<div class="tp-checkout-agree mt-4">
							<div class="tp-checkout-option">
								<input id="read_all" type="checkbox" required> {{-- Added required --}}
								<label for="read_all" class="ml-1 text-sm text-gray-600 dark:text-gray-400">I have read and
									agree to the <a href="#" class="text-indigo-600 hover:underline">website terms and
										conditions</a>.</label> {{-- Added minor styling --}}
							</div>
						</div>
						<div class="tp-checkout-btn-wrapper mt-6">
							{{-- Submit button for the form --}}
							<button type="submit"
								class="tp-btn-cart sm w-100 block text-center opacity-50 cursor-not-allowed"
								id="select-datetime-btn" disabled>Select Date & Time</button> {{-- Button initially disabled
							--}}
						</div>
						</form> {{-- Form ends here --}}
					</div>
				</div>
			</div>
		</div>
	</section>

	<script>
		const services = [
			{ id: "soak_off_gel", name: "Gel/Stickons Soak Off", price: 200 },
			{ id: "soak_off_acrylics", name: "Acrylics, Gumgel Soak Off", price: 500 },
			{ id: "manicure_plain", name: "Plain Manicure", price: 500 },
			{ id: "manicure_gel", name: "Manicure + Gel", price: 1500 },
			{ id: "pedicure_plain", name: "Plain Pedicure", price: 1000 },
			{ id: "pedicure_gel", name: "Pedicure + Gel", price: 2000 },
			{ id: "pedicure_jelly_scrub_gel", name: "Jelly Pedicure + Scrub + Gel", price: 3000 },
			{ id: "pedicure_jelly_scrub_massage_gel", name: "Jelly Pedicure + Scrub + Honey Massage + Gel", price: 3500 },
			{ id: "stickons_tips", name: "Tips with Gel", price: 2000 },
			{ id: "sculpted_plain", name: "Plain Sculpted Acrylics", price: 4000 },
			{ id: "sculpted_ombre", name: "Sculpted Ombre", price: 4500 },
			{ id: "sculpted_encapsulated", name: "Encapsulated Nail Art", price: 4500 },
			{ id: "toe_gel", name: "Gel on Toes", price: 1000 },
			{ id: "toe_tips_gel", name: "Tips on Toes + Gel", price: 2000 },
			{ id: "toe_acrylic_plain", name: "Plain Acrylic Toe Nails", price: 2500 },
			{ id: "toe_french", name: "French Toe Nails", price: 3000 },
			{ id: "overlay_gel", name: "Overlay with Gel", price: 2000 },
			{ id: "overlay_powder", name: "Powder Overlays", price: 2500 },
			{ id: "acrylic_plain", name: "Plain Colour Acrylics", price: 3000 },
			{ id: "acrylic_colored", name: "Coloured Acrylics", price: 3500 },
			{ id: "acrylic_refill", name: "Acrylics Refill", price: 2500 },
			{ id: "acrylic_long", name: "Acrylics Long Nails", price: 4000 },
			{ id: "acrylic_extra", name: "Acrylics (Extra Art, Design, Stones & Glitters)", price: 4500 }
		];

		const servicesContainer = document.getElementById("services");
		const selectedInput = document.getElementById("selected-services");
		const orderSummaryDiv = document.getElementById("order-summary"); // Target the div
		const subtotalText = document.getElementById("subtotal");
		const totalText = document.getElementById("total"); // Target the element with ID 'total'
		const agreeCheckbox = document.getElementById("read_all");
		const selectDatetimeBtn = document.getElementById("select-datetime-btn");


		// Render checkboxes
		if (servicesContainer) { // Check if container exists
			services.forEach(service => {
				const serviceHTML = `
						<div class="flex items-center"> {{-- Added flex for better alignment --}}
							<input type="checkbox" class="service-checkbox mr-2" id="${service.id}" data-name="${service.name}" data-price="${service.price}"> {{-- Added mr-2 --}}
							{{-- FIX: Use JavaScript template literal syntax $ { } instead of Blade syntax {{ }} --}}
							<label for="${service.id}" class="text-gray-700 dark:text-gray-300 text-sm cursor-pointer">${service.name} - Ksh ${service.price.toFixed(2)}</label> {{-- Added styling and formatting --}}
						</div>
					`;
				// Using insertAdjacentHTML is slightly better for performance than +=
				servicesContainer.insertAdjacentHTML('beforeend', serviceHTML);
			});
		}


		const updateSummary = () => {
			const checkedBoxes = [...document.querySelectorAll(".service-checkbox:checked")];

			// Implement the 2-service limit more robustly
			if (checkedBoxes.length > 2) {
				// Uncheck the box that was just checked
				const lastChecked = checkedBoxes[checkedBoxes.length - 1];
				lastChecked.checked = false;
				alert("You can only select up to 2 services.");
				// Recalculate summary after unchecking
				updateSummary(); // Call itself again with the corrected selection
				return; // Stop further execution in this call
			}

			let selected = [];
			let subtotal = 0;
			orderSummaryDiv.innerHTML = ""; // Clear previous items in the div

			if (checkedBoxes.length === 0) {
				orderSummaryDiv.innerHTML = '<p class="text-gray-500 text-center">No items selected</p>';
			} else {
				checkedBoxes.forEach(cb => {
					const name = cb.getAttribute("data-name");
					const price = parseFloat(cb.getAttribute("data-price"));
					subtotal += price;
					selected.push(`${name}|${price}`);

					const item = document.createElement("li");
					item.classList.add("tp-order-info-list-desc"); // Use the class from your HTML
					item.classList.add("flex", "justify-between", "py-1", "border-b", "border-gray-100"); // Add some Tailwind classes for styling
					item.innerHTML = `<p class="text-sm text-gray-700">${name}</p><span class="text-sm font-medium text-gray-900">Ksh ${price.toFixed(2)}</span>`;
					orderSummaryDiv.appendChild(item);
				});
			}


			selectedInput.value = selected.join(", ");
			subtotalText.textContent = `Ksh ${subtotal.toFixed(2)}`;
			totalText.textContent = `Ksh ${subtotal.toFixed(2)}`; // Update the correct total span

			// Enable/Disable the submit button based on selection and agreement
			updateButtonState();
		};

		const updateButtonState = () => {
			const checkedBoxes = [...document.querySelectorAll(".service-checkbox:checked")];
			const isAgreed = agreeCheckbox.checked;
			const hasSelection = checkedBoxes.length > 0;

			if (isAgreed && hasSelection) {
				selectDatetimeBtn.disabled = false;
				selectDatetimeBtn.classList.remove("opacity-50", "cursor-not-allowed");
				selectDatetimeBtn.classList.add("bg-indigo-600", "hover:bg-indigo-700"); // Add active styles
			} else {
				selectDatetimeBtn.disabled = true;
				selectDatetimeBtn.classList.add("opacity-50", "cursor-not-allowed");
				selectDatetimeBtn.classList.remove("bg-indigo-600", "hover:bg-indigo-700"); // Remove active styles
			}
		};

		// Attach event listeners after the DOM is fully loaded
		document.addEventListener("DOMContentLoaded", () => {
			// Attach change listener to all checkboxes
			document.querySelectorAll(".service-checkbox").forEach(cb => {
				cb.addEventListener("change", updateSummary);
			});

			// Attach change listener to the agreement checkbox
			if (agreeCheckbox) { // Check if checkbox exists
				agreeCheckbox.addEventListener("change", updateButtonState);
			}

			// Initial update on page load (useful if services could be pre-selected)
			// updateSummary(); // Uncomment if you need to handle pre-checked boxes
			updateButtonState(); // Set initial button state
		});
	</script>

@endsection