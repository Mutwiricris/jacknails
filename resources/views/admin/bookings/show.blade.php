@extends('layouts.dash')

@section('content')
	<div class="container mx-auto px-4 py-6">
		<div class="flex items-center justify-between mb-6">
			<h1 class="text-2xl font-bold">Booking Details (#{{ $booking->id }})</h1>
			<a href="{{ route('admin.bookings') }}"
				class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
				Back to Bookings
			</a>
		</div>


		@if (session('success'))
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
				<span class="block sm:inline">{{ session('success') }}</span>
			</div>
		@endif

		<div class="bg-white rounded-lg shadow p-6 mb-6">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				{{-- Customer Details --}}
				<div>
					<h2 class="text-xl font-semibold mb-4 border-b pb-2">Customer Details</h2>
					<p class="mb-2"><span class="font-medium">Name:</span> {{ $booking->first_name }}
						{{ $booking->last_name }}</p>
					<p class="mb-2"><span class="font-medium">Email:</span> {{ $booking->email }}</p>
					@if ($booking->phone)
						<p class="mb-2"><span class="font-medium">Phone:</span> {{ $booking->phone }}</p>
					@endif
				</div>

				{{-- Booking Details --}}
				<div>
					<h2 class="text-xl font-semibold mb-4 border-b pb-2">Booking Details</h2>
					<p class="mb-2"><span class="font-medium">Date:</span>
						{{ \Carbon\Carbon::parse($booking->date)->format('F d, Y') }}</p>
					<p class="mb-2"><span class="font-medium">Time:</span> {{ optional($booking->timeSlot)->time ?? 'N/A' }}
					</p>
					<p class="mb-2"><span class="font-medium">Status:</span>
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
					</p>
					@if ($booking->is_completed)
						<p class="mb-2"><span class="font-medium">Completed At:</span>
							{{ \Carbon\Carbon::parse($booking->completed_at)->format('F d, Y H:i') }}</p>
					@endif
					<p class="mb-2"><span class="font-medium">Booked At:</span>
						{{ \Carbon\Carbon::parse($booking->created_at)->format('F d, Y H:i') }}</p>

				</div>
			</div>

			{{-- Services --}}
			<div class="mt-6">
				<h2 class="text-xl font-semibold mb-4 border-b pb-2">Services Booked</h2>
				@if ($booking->services)
					<ul class="list-disc pl-5">
						@foreach ($booking->services as $service)
							<li>{{ $service['name'] }} - Ksh{{ number_format($service['price'], 2) }}</li>
						@endforeach
					</ul>
				@else
					<p>No services listed.</p>
				@endif
			</div>

			{{-- Total and Notes --}}
			<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
				<div>
					<h2 class="text-xl font-semibold mb-4 border-b pb-2">Total Price</h2>
					<p class="text-2xl font-bold text-indigo-600">Ksh{{ number_format($booking->total_price, 2) }}</p>
				</div>
				@if ($booking->order_notes)
					<div>
						<h2 class="text-xl font-semibold mb-4 border-b pb-2">Order Notes</h2>
						<p class="text-gray-700">{{ $booking->order_notes }}</p>
					</div>
				@endif
			</div>

			{{-- Actions --}}
			@if (!$booking->is_completed)
				<div class="mt-6 border-t pt-6">
					<h2 class="text-xl font-semibold mb-4">Actions</h2>
					<form action="{{ route('admin.booking.complete', $booking->id) }}" method="POST">
						@csrf
						<button type="submit"
							class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
							Mark as Completed
						</button>
					</form>
				</div>
			@endif
		</div>
	</div>
@endsection