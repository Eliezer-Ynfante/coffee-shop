<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;

class ReservationSchedule
{
    public function slots(): array
    {
        $openingTime = Setting::get('reservation_open_time');
        $closingTime = Setting::get('reservation_close_time');

        if (! is_string($openingTime) || ! is_string($closingTime) || $openingTime === '' || $closingTime === '') {
            return config('cafe.turnos_horarios', []);
        }

        try {
            $opening = Carbon::createFromFormat('!H:i', $openingTime);
            $closing = Carbon::createFromFormat('!H:i', $closingTime);
        } catch (\Throwable) {
            return config('cafe.turnos_horarios', []);
        }

        if (! $opening || ! $closing || $closing->lessThanOrEqualTo($opening)) {
            return config('cafe.turnos_horarios', []);
        }

        $interval = max(15, (int) Setting::get('reservation_slot_interval_minutes', 90));
        $lastStart = $closing->copy()->subMinutes($this->durationMinutes() + $this->bufferMinutes());
        $slots = [];

        for ($slot = $opening->copy(); $slot->lessThanOrEqualTo($lastStart); $slot->addMinutes($interval)) {
            $slots[] = $slot->format('h:i A');
        }

        return $slots;
    }

    public function durationMinutes(): int
    {
        return max(30, (int) Setting::get('reservation_slot_duration_minutes', config('cafe.slot_duration_minutes', 90)));
    }

    public function bufferMinutes(): int
    {
        return max(0, (int) Setting::get('reservation_buffer_minutes', config('cafe.slot_buffer_minutes', 15)));
    }
}