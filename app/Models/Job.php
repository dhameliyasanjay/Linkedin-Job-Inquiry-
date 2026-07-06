<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position_id',
        'state',
        'city',
        'experience',
        'phone_number',
        'payment',
        'start_date',
        'end_date',
        'plan_duration',
        'status',
    ];

    /**
     * The plan duration options available for selection.
     */
    public const PLAN_DURATIONS = [
        '1 Month'   => '1 Month',
        '2 Months'  => '2 Months',
        '3 Months'  => '3 Months',
        '6 Months'  => '6 Months',
        '1 Year'    => '1 Year',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    /**
     * Determine if the job has expired based on end_date vs today.
     * This is a computed check — no DB write required.
     */
    public function isExpired(): bool
    {
        return $this->end_date !== null && $this->end_date->isPast();
    }

    /**
     * Get the current status, dynamically reflecting expiry state.
     * If end_date has passed, the job is effectively Inactive.
     */
    public function getCurrentStatusAttribute(): string
    {
        return $this->isExpired() ? 'Inactive' : 'Active';
    }

    /**
     * Get the position that owns the job.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
