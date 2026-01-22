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



    public function index()
    {
        $messages = $this->messageRepository->getSendedMessages();

        if (!$messages) {
            return response()->json([
                'status' => 'error',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }

    public function cachedMessages($id)
    {
        $messages = $this->messageRepository->getCachedMessages($id);

        if (!$messages) {
            return response()->json([
                'status' => 'error',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }
}
