<?php

namespace App\Services;

use App\Models\Message;
use App\Repositories\MessageRepository;

class MessageServices {

    protected $repository; 
    public function __construct(MessageRepository $message)
    {
        $this->repository = $message;
    }
    
    public function validateMessageContent($message) {
        return mb_strlen($message) <= Message::MAX_CONTENT_LENGTH;
    }

    public function handleDeliveryStatus(int $id ,?string $message_id) {
        if ($message_id) {
            $this->repository->cacheSentMessages($id, now(), $message_id);
            return $this->repository->markAsSent($id,$message_id);
        }

        return $this->repository->markAsFailed($id);
    }
}