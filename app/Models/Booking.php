<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'order_notes',
        'date',
        'time_slot_id',
        'services', // This will be JSON stored
        'total_price',
        'is_completed',
        'completed_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'services' => 'array',
        'total_price' => 'float',
        'is_completed' => 'boolean',
        'date' => 'date',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the time slot associated with the booking.
     */
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    /**
     * Get the customer's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get formatted services list.
     */
    public function getServiceListAttribute()
    {
        if (!$this->services) {
            return '';
        }
        
        return collect($this->services)
            ->map(function ($service) {
                return $service['name'] . ' ($' . number_format($service['price'], 2) . ')';
            })
            ->implode(', ');
    }
}