<?php

namespace App\Console\Commands;

use App\Jobs\SendMessageJob;
use Illuminate\Console\Command;
use App\Repositories\MessageRepository;

class SendBulkMessages extends Command
{
    protected $signature = 'app:send-bulk-messages';
    protected $description = 'Starts sending bulk messages from the messages table';

    /**
     * Execute the console command.
     */
    public function handle(MessageRepository $messageRepository)
    {
        $messages = $messageRepository->getPendingMessages();
        $this->info("Sending " . count($messages) . " messages..."); 

        $batchSize = 2;
        $intervalSeconds = 5;
        $processedCount = 0;
        
        foreach ($messages as $message) {
            $delayInSeconds = floor($processedCount / $batchSize) * $intervalSeconds;
            SendMessageJob::dispatch($message)
                ->delay(now()->addSeconds($delayInSeconds));
            
            $message->status = 'queued';
            $message->save();
            $this->info("Dispatched message ID: {$message->id} with delay of {$delayInSeconds} seconds");
            $processedCount++;
        }

        $this->info('All messages have been dispatched for sending.');
    }
}
