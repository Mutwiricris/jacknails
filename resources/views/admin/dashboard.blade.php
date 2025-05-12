@extends('layouts.dash')

@section('content')
	<div class="container mx-auto px-4 py-6">
		<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

		{{-- Display Success/Error Messages --}}
		@if (session('success'))
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
				<span class="block sm:inline">{{ session('success') }}</span>
			</div>
		@endif
		@if (session('error'))
			<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
				<span class="block sm:inline">{{ session('error') }}</span>
			</div>
		@endif


		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
			<div class="bg-white rounded-lg shadow p-6">
				<div class="flex justify-between items-center mb-2">
					<h3 class="text-gray-500 text-sm font-medium">Total Bookings</h3>
					<span class="p-2 bg-blue-100 rounded-full">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20"
							fill="currentColor">
							<path fill-rule="evenodd"
								d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
								clip-rule="evenodd" />
						</svg>
					</span>
				</div>
				<div class="flex items-baseline">
					<h2 class="text-3xl font-bold">{{ $stats['total_bookings'] ?? 0 }}</h2>
					<span class="ml-2 text-sm text-gray-500">bookings</span>
				</div>
			</div>

			<div class="bg-white rounded-lg shadow p-6">
				<div class="flex justify-between items-center mb-2">
					<h3 class="text-gray-500 text-sm font-medium">Completed Bookings</h3>
					<span class="p-2 bg-green-100 rounded-full">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20"
							fill="currentColor">
							<path fill-rule="evenodd"
								d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
								clip-rule="evenodd" />
						</svg>
					</span>
				</div>
				<div class="flex items-baseline">
					<h2 class="text-3xl font-bold">{{ $stats['completed_bookings'] ?? 0 }}</h2>
					<span class="ml-2 text-sm text-gray-500">completed</span>
					{{-- Calculate percentage safely --}}
					@php
						$completionPercentage = 0;
						if (($stats['total_bookings'] ?? 0) > 0) {
							$completionPercentage = round((($stats['completed_bookings'] ?? 0) / $stats['total_bookings']) * 100);
						}
					@endphp
					<span class="ml-2 text-sm text-gray-400">({{ $completionPercentage }}%)</span>
				</div>
			</div>

			<div class="bg-white rounded-lg shadow p-6">
				<div class="flex justify-between items-center mb-2">
					<h3 class="text-gray-500 text-sm font-medium">Total Revenue (Completed)</h3>
					<span class="p-2 bg-purple-100 rounded-full">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20"
							fill="currentColor">
							<path
								d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
							<path fill-rule="evenodd"
								d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
								clip-rule="evenodd" />
						</svg>
					</span>
				</div>
				<div class="flex items-baseline">
					<h2 class="text-3xl font-bold">Ksh{{ number_format($stats['total_revenue'] ?? 0, 2) }}</h2>
				</div>
			</div>

			<div class="bg-white rounded-lg shadow p-6">
				<div class="flex justify-between items-center mb-2">
					<h3 class="text-gray-500 text-sm font-medium">Today's Bookings</h3>
					<span class="p-2 bg-yellow-100 rounded-full">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20"
							fill="currentColor">
							<path fill-rule="evenodd"
								d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
								clip-rule="evenodd" />
						</svg>
					</span>
				</div>
				<div class="flex items-baseline">
					<h2 class="text-3xl font-bold">{{ $stats['today_bookings'] ?? 0 }}</h2>
					<span class="ml-2 text-sm text-gray-500">today</span>
				</div>
			</div>
		</div>

		<div class="bg-white rounded-lg shadow overflow-hidden mb-8"> {{-- Added margin-bottom --}}
			<div class="px-6 py-4 border-b">
				<h2 class="text-xl font-semibold">Recent Bookings</h2>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
							</th>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
								& Time</th>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Services</th>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
							</th>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Status</th>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($recentBookings as $booking)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="font-medium text-gray-900">{{ $booking->first_name }} {{ $booking->last_name }}
									</div> {{-- Use first_name and last_name --}}
									<div class="text-sm text-gray-500">{{ $booking->email }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									{{-- Use date and timeSlot --}}
									<div class="text-sm text-gray-900">
										{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }} at
										{{ optional($booking->timeSlot)->time ?? 'N/A' }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900">
										{{-- Use array_column instead of pluck() --}}
										@if (is_array($booking->services))
											{{ implode(', ', array_column($booking->services, 'name')) }}
										@else
											N/A
										@endif
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									{{-- Use total_price --}}
									<div class="text-sm text-gray-900">Ksh{{ number_format($booking->total_price, 2) }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									{{-- Check is_completed for status --}}
									@if ($booking->is_completed)
										<span
											class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
											Completed
										</span>
									@else
										<span
											class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
											Pending
										</span>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
									<a href="{{ route('admin.booking.show', $booking->id) }}"
										class="text-indigo-600 hover:text-indigo-900">View</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="px-6 py-4 text-center text-gray-500">No recent bookings found.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		{{-- Calendar Section --}}
		<div class="bg-white rounded-lg shadow p-6">
			<h2 class="text-xl font-semibold mb-4">Booking Calendar</h2>
			<div id='calendar'></div>
		</div>

	</div>

	{{-- FullCalendar CSS --}}
	<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

	{{-- FullCalendar JS --}}
	<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
	{{-- FullCalendar Interaction Plugin (for clickable dates/events) --}}
	<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@5.11.3/main.min.js'></script>
	{{-- FullCalendar DayGrid Plugin (for month view) --}}
	<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@5.11.3/main.min.js'></script>
	{{-- FullCalendar TimeGrid Plugin (optional, for week/day views) --}}
	{{--
	<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@5.11.3/main.min.js'></script> --}}


	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var calendarEl = document.getElementById('calendar');
			var calendar = new FullCalendar.Calendar(calendarEl, {
				plugins: ['daygrid', 'interaction'], // Use daygrid for month view, interaction for clicking
				initialView: 'dayGridMonth', // Start with month view
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay' // Add timeGrid views if needed
				},
				editable: false, // Set to true if you want to allow dragging/resizing events
				selectable: true, // Allow selecting dates
				selectMirror: true,
				// eventLimit: true, // allow "more" link when too many events

				// --- Event Source ---
				// This is where FullCalendar fetches your booking data.
				// You need a Laravel route that returns JSON data formatted for FullCalendar events.
				events: {
					url: '{{ route('admin.bookings.calendar-events') }}', // Example route name
					method: 'GET',
					failure: function () {
						alert('There was an error while fetching bookings!');
					},
					// Optional: Add extra parameters to the AJAX request
					// extraParams: function() {
					//     return {
					//         custom_param: 'something',
					//     };
					// }
				},

				// --- Event Click Handler ---
				// When a user clicks on a booking event
				eventClick: function (info) {
					// info.event contains the event object
					// info.jsEvent contains the native DOM event
					// info.view contains the current view object

					// Example: Redirect to the booking details page
					if (info.event.url) {
						window.open(info.event.url); // Open in a new tab
						// Or: window.location.href = info.event.url; // Open in the same tab
					}
				},

				// --- Date Click Handler (Optional) ---
				// When a user clicks on a date (not an event)
				// dateClick: function(info) {
				//     alert('Clicked on: ' + info.dateStr);
				//     // Example: Redirect to a page to create a new booking for this date
				//     // window.location.href = '/admin/bookings/create?date=' + info.dateStr;
				// },

				// --- Customize Event Appearance (Optional) ---
				eventDidMount: function (info) {
					// You can customize the appearance of events here
					// Example: Add a tooltip with more details
					// $(info.el).tooltip({
					//     title: info.event.extendedProps.description, // Assuming you add a 'description' to your event object
					//     placement: 'top'
					// });
				},

				// --- Customize Event Data Mapping (Optional) ---
				// If your event data from the server has different field names,
				// you can map them here. FullCalendar expects 'title', 'start', 'end' (optional), 'url' (optional), etc.
				// eventDataTransform: function(eventData) {
				//     return {
				//         title: eventData.customer_name + ' - ' + eventData.service_name, // Example mapping
				//         start: eventData.booking_date + 'T' + eventData.booking_time, // Combine date and time if separate
				//         url: '/admin/bookings/' + eventData.booking_id, // Example URL
				//         // Add other standard FullCalendar properties as needed
				//     };
				// }

			});

			calendar.render();
		});
	</script>
@endsection