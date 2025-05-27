<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Morilog\Jalali\Jalalian;

class EventController extends Controller
{
    /**
     * ذخیره رویداد جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|string|regex:/^\d{4}\/\d{2}\/\d{2}$/',
            'time' => 'required|string|regex:/^\d{2}:\d{2}$/',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $jalaliDate = Jalalian::fromFormat('Y/m/d', $validated['date'])->toCarbon();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid date format.'
            ], 400);
        }

        $event = Event::create([
            'date' => $jalaliDate,
            'time' => $validated['time'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'message' => 'Event stored successfully.',
            'data' => $event
        ], 201);
    }
}
