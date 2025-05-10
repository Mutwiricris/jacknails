@extends('layouts.dash')

@section('content')
	<div class="container mx-auto px-4 py-6">
		<h1 class="text-2xl font-bold mb-6">Booking Reports</h1>

		{{-- Period Selection Form --}}
		<div class="bg-white rounded-lg shadow p-6 mb-6">
			<h2 class="text-xl font-semibold mb-4">Select Report Period</h2>
			<form action="{{ route('admin.reports') }}" method="GET"
				class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
				<div>
					<label for="period" class="block text-sm font-medium text-gray-700">Period</label>
					<select name="period" id="period"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
						<option value="week" {{ $period == 'week' ? 'selected' : '' }}>Last 7 Days</option>
						<option value="month" {{ $period == 'month' ? 'selected' : '' }}>Last 30 Days</option>
						<option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Last 3 Months</option>
						<option value="year" {{ $period == 'year' ? 'selected' : '' }}>Last Year</option>
						<option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
					</select>
				</div>

				{{-- Custom Date Range (Shown only if period is custom) --}}
				<div id="custom-date-range"
					class="{{ $period == 'custom' ? 'block' : 'hidden' }} md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
						<input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
							class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
					</div>
					<div>
						<label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
						<input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
							class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
					</div>
				</div>

				<div class="md:col-span-3 flex justify-end">
					<button type="submit"
						class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						Generate Report
					</button>
				</div>
			</form>
		</div>

		{{-- Report Summary --}}
		<div class="bg-white rounded-lg shadow p-6 mb-6">
			<h2 class="text-xl font-semibold mb-4 border-b pb-2">Report Summary
				@if ($startDate && $endDate)
					({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})
				@endif
			</h2>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<div>
					<p class="text-gray-700 text-sm font-medium mb-2">Total Completed Bookings:</p>
					<p class="text-3xl font-bold text-indigo-600">{{ $totalCount }}</p>
				</div>
				<div>
					<p class="text-gray-700 text-sm font-medium mb-2">Total Revenue:</p>
					<p class="text-3xl font-bold text-green-600">Ksh{{ number_format($totalRevenue, 2) }}</p>
				</div>
			</div>
		</div>

		{{-- Service Statistics --}}
		@if (!empty($serviceStats))
			<div class="bg-white rounded-lg shadow p-6 mb-6">
				<h2 class="text-xl font-semibold mb-4 border-b pb-2">Service Statistics</h2>
				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-50">
							<tr>
								<th scope="col"
									class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									Service
								</th>
								<th scope="col"
									class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									Bookings Count
								</th>
								<th scope="col"
									class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									Revenue
								</th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							@foreach ($serviceStats as $serviceName => $stats)
								<tr>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm font-medium text-gray-900">{{ $serviceName }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900">{{ $stats['count'] }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900">KSh {{ number_format($stats['revenue'], 2) }}</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@endif

		{{-- Daily Revenue (Simple List - For Charting consider a JS library) --}}
		@if (!$dailyRevenue->isEmpty())
			<div class="bg-white rounded-lg shadow p-6 mb-6">
				<h2 class="text-xl font-semibold mb-4 border-b pb-2">Daily Revenue</h2>
				<ul class="list-disc pl-5">
					@foreach ($dailyRevenue->sortBy(fn($revenue, $date) => $date) as $date => $revenue)
						<li>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}: Ksh {{ number_format($revenue, 2) }}</li>
					@endforeach
				</ul>
			</div>
		@endif

	</div>

	<script>
		// Show/hide custom date range inputs based on period selection
		document.getElementById('period').addEventListener('change', function () {
			const customRangeDiv = document.getElementById('custom-date-range');
			if (this.value === 'custom') {
				customRangeDiv.classList.remove('hidden');
			} else {
				customRangeDiv.classList.add('hidden');
			}
		});
	</script>
@endsection