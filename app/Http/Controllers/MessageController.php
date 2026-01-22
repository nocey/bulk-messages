<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepository;


class MessageController extends Controller
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }


    /**
     * List Sent Messages
     *
     * Returns a list of all messages that have been successfully sent.
     * The list is retrieved from the MySQL database.
     *
     * @response array{
     * status: "success",
     * data: array<int, array{
     * id: 1,
     * phone: "+905551234567",
     * content: "Hello World",
     * status: "sent",
     * message_id: "api-id-123",
     * sent_at: "2024-01-01 12:00:00"
     * }>
     * }
     */
    public function index()
    {

        $messages = $this->messageRepository->getSendedMessages();

        if (!$messages) {
            return response()->json([
                'status' => 'error',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ],200);
    }
    /**
     * Get Cached Message Detail
     *
     * Retrieves the details of a specific message from Redis cache.
     *
     * @param int $id The ID of the message
     * * @response array{
     * status: "success",
     * data: array{
     * sent_at: "2024-01-01 12:00:00",
     * api_message_id: "api-id-123"
     * }
     * }
     * * @response 404 array{
     * status: "error",
     * data: null,
     * message: "Message not found in cache"
     * }
     */
    public function cachedMessages($id)
    {
        $messages = $this->messageRepository->getCachedMessages($id);

        if (!$messages) {
            return response()->json([
                'status' => 'error',
                'data' => null
            ],404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ],200);
    }
}
