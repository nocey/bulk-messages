<?php

namespace App\Repositories;

use \App\Models\Message;
use Illuminate\Support\Facades\Redis;

class MessageRepository
{

    public function getSendedMessages() {
        return Message::where('status','sent')->get();
    }
    public function getPendingMessages()
    {
        return Message::where('status', 'pending')->get();
    }

    public function markAsSent($id,$message_id): bool
    {
        return $this->changeStatus($id, Message::STATUS_SENT, $message_id);
    }

    public function markAsPending($id): bool
    {
        return $this->changeStatus($id, Message::STATUS_PENDING);
    }

    public function markAsFailed($id): bool
    {
        return $this->changeStatus($id, Message::STATUS_FAILED);
    }

    public function cacheSentMessages($id, $sent_at, $message_id) {
        Redis::set("message_sent:{$id}", json_encode(['sent_at' => $sent_at, "message_id" => $message_id]), 3600);
    }

    public function getCachedMessages($id) {
        $message = Redis::get("message_sent:{$id}");
        return $message ? json_decode($message, true) : null;
    }

    private function changeStatus($id, $status, $message_id = null): bool
    {
        $message = Message::find($id);
        if ($message) {
            $message->status = $status;
            if($message_id){
                $message->message_id = $message_id;
            }
            $message->save();
            return true;
        }

        return false;
    }
}