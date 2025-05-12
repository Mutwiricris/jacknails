@forelse($timeSlots as $timeSlot)
	<div>
		<input type="radio" id="time-{{ $timeSlot->id }}" value="{{ $timeSlot->id }}" name="time_slot_id"
			class="peer hidden" required>
		<label for="time-{{ $timeSlot->id }}"
			class="block text-center p-3 border rounded-md cursor-pointer text-sm transition duration-150 ease-in-out
					   hover:bg-gray-100 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600
					   dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 dark:peer-checked:bg-indigo-700 dark:peer-checked:text-white dark:peer-checked:border-indigo-700">
			{{ \Carbon\Carbon::parse($timeSlot->time)->format('h:i A') }}
		</label>
	</div>
@empty
	<div class="col-span-2 text-center p-4 border rounded-md text-gray-600 dark:text-gray-400">
		No time slots available for this date.
	</div>
@endforelse