@extends('layouts.dash')

@section('content')
	<div class="container mx-auto px-4 py-6">
		<h1 class="text-2xl font-bold mb-6">All Bookings</h1>

		@if (session('success'))
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
				<span class="block sm:inline">{{ session('success') }}</span>
			</div>
		@endif

		{{-- Filter and Search Form --}}
		<div class="bg-white rounded-lg shadow p-6 mb-6">
			<h2 class="text-xl font-semibold mb-4">Filter and Search</h2>
			<form action="{{ route('admin.bookings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
				<div>
					<label for="status" class="block text-sm font-medium text-gray-700">Status</label>
					<select name="status" id="status"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
						<option value="">All</option>
						<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
						<option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
					</select>
				</div>
				<div>
					<label for="date" class="block text-sm font-medium text-gray-700">Date</label>
					<input type="date" name="date" id="date" value="{{ request('date') }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
				</div>
				<div>
					<label for="search" class="block text-sm font-medium text-gray-700">Search (Name or Email)</label>
					<input type="text" name="search" id="search" value="{{ request('search') }}"
						placeholder="Enter name or email"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
				</div>
				<div class="md:col-span-3 flex justify-end">
					<button type="submit"
						class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						Apply Filters
					</button>
					@if(request()->has('status') || request()->has('date') || request()->has('search'))
						<a href="{{ route('admin.bookings') }}"
							class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
							Clear Filters
						</a>
					@endif
				</div>
			</form>
		</div>


		{{-- Bookings Table --}}
		<div class="bg-white rounded-lg shadow overflow-hidden">
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
							</th>
							<th scope="col"
								class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								Contact
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
						@forelse($bookings as $booking)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="font-medium text-gray-900">{{ $booking->first_name }} {{ $booking->last_name }}
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-500">{{ $booking->email }}</div>
									@if ($booking->phone)
										<div class="text-sm text-gray-500">{{ $booking->phone }}</div>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900">
										{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }} at
										{{ optional($booking->timeSlot)->time ?? 'N/A' }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900">
										@foreach ($booking->services as $service)
											{{ $service['name'] }}{{ !$loop->last ? ', ' : '' }}
										@endforeach
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900">Ksh{{ number_format($booking->total_price, 2) }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
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
										class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
									{{-- Add Edit/Delete if needed --}}
									{{-- <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a> --}}
									{{-- <button type="button" class="text-red-600 hover:text-red-900">Delete</button> --}}
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings found matching your
									criteria.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			{{-- Pagination --}}
			<div class="px-6 py-4">
				{{ $bookings->links() }}
			</div>
		</div>
	</div>
@endsection