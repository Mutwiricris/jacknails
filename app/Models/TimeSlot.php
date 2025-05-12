<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
	//
	protected $fillable = ['time', 'active'];

	protected $casts = [
		'active' => 'boolean',
	];

	// In app/Models/Booking.php
	public function bookings()
	{
		return $this->hasMany(Booking::class);
	}



}
