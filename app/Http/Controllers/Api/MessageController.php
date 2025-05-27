<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/messages",
     *     summary="Store a new message",
     *     description="Stores a new message sent by the authenticated user. Message content must be a non-empty string. This request requires a valid authentication token",
     *     tags={"Messages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Hello, I had a question about the shotgun.",
     *                 description="The content of the message. Must be a non-empty string."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message successfully stored",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Message stored successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="message", type="string", example="Hello, I had a question about the shotgun."),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-27T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-27T10:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="message", type="array",
     *                     @OA\Items(type="string", example="The message field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token missing or invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Message stored successfully', 'data' => $message]);
    }
}
