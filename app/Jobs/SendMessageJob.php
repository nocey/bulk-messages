<?php

namespace App\Jobs;

use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http; 
use App\Services\MessageServices;
use Throwable;

class SendMessageJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue,Queueable, SerializesModels;

    protected Message $message;
    
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function failed(?Throwable $exception): void
    {
        $service = app(MessageServices::class);
        
        $service->handleDeliveryStatus($this->message->id, null);
    }


    public function handle(MessageServices $messageServices): void
    {
        if (!$messageServices->validateMessageContent($this->message->content)) {
            $messageServices->handleDeliveryStatus($this->message->id, null);
            return;
        }

        $response = Http::post('https://webhook.site/269c75df-165e-406a-8b5b-a24420e67f69', [
            'to' => $this->message->phone,
            'content' => $this->message->content,
        ]);
        if ($response->failed()) {
            echo "❌ [HTTP Error] Message ID: {$this->message->id}\n";
            echo "   Status Code: " . $response->status() . "\n";
            echo "   Reason: " . $response->body() . "\n";
            
            $messageServices->handleDeliveryStatus($this->message->id, null);
            return;
        }

        $messageServices->handleDeliveryStatus(
            $this->message->id,
            $response->successful() ? $response->json()['messageId'] : null
        );

    }
}
