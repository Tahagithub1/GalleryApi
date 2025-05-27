<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Morilog\Jalali\Jalalian;

class EventController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Store a new event",
     *     description="Stores a new event using a Jalali date. Requires a valid authentication token in the header.",
     *     tags={"Event"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "time"},
     *             @OA\Property(property="date", type="string", example="1403/05/06", description="Jalali date in Y/m/d format"),
     *             @OA\Property(property="time", type="string", example="14:30", description="Time in HH:mm format"),
     *             @OA\Property(property="description", type="string", example="test", description="Optional description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Event stored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Event stored successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-07-28T00:00:00.000000Z"),
     *                 @OA\Property(property="time", type="string", example="14:30"),
     *                 @OA\Property(property="description", type="string", example="test"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-27T15:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-27T15:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid date format",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid date format.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
