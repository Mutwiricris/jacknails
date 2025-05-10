@extends('layouts.dash')

@section('content')
	<div class="container mx-auto px-4 py-6">
		<h1 class="text-2xl font-bold mb-6">Manage Time Slots</h1>

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

		{{-- Add New Time Slot Form --}}
		<div class="bg-white rounded-lg shadow p-6 mb-6">
			<h2 class="text-xl font-semibold mb-4">Add New Time Slot</h2>
			<form action="{{ route('admin.timeslots.store') }}" method="POST"
				class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
				@csrf
				<div>
					<label for="time" class="block text-sm font-medium text-gray-700">Time (e.g., 09:00, 14:30)</label>
					<input type="text" name="time" id="time" value="{{ old('time') }}" required
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('time') border-red-500 @enderror">
					@error('time')
						<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div class="md:col-span-2 flex justify-end">
					<button type="submit"
						class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						Add Time Slot
					</button>
				</div>
			</form>
		</div>

		{{-- Time Slots List --}}
		<div class="bg-white rounded-lg shadow overflow-hidden">
			<div class="px-6 py-4 border-b">
				<h2 class="text-xl font-semibold">Existing Time Slots</h2>
			</div>
			<div class="overflow-x-auto">
				@if ($timeSlots->isEmpty())
					<p class="px-6 py-4 text-center text-gray-500">No time slots found.</p>
				@else
					<table class="min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-50">
							<tr>
								<th scope="col"
									class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time
								</th>
								<th scope="col"
									class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									Status
								</th>
								<th scope="col"
									class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									Created At
								</th>
								<th scope="col"
									class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									Actions
								</th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							@foreach($timeSlots as $timeSlot)
								<tr>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900">{{ $timeSlot->time }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										@if ($timeSlot->active)
											<span
												class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
												Active
											</span>
										@else
											<span
												class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
												Inactive
											</span>
										@endif
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-500">
											{{ \Carbon\Carbon::parse($timeSlot->created_at)->format('Y-m-d H:i') }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
										{{-- Reactivate button (if you implement deactivation) --}}
										{{-- @if (!$timeSlot->active)
										<form action="{{ route('admin.timeslot.reactivate', $timeSlot->id) }}" method="POST"
											class="inline">
											@csrf
											<button type="submit"
												class="text-green-600 hover:text-green-900 mr-3">Reactivate</button>
										</form>
										@endif --}}

										{{-- Delete form --}}
										<form action="{{ route('admin.timeslots.delete', $timeSlot->id) }}" method="POST"
											class="inline"
											onsubmit="return confirm('Are you sure you want to delete this time slot?');">
											@csrf
											@method('DELETE')
											<button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@endif
			</div>
		</div>
	</div>
@endsection