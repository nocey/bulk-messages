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

        foreach ($messages as $index=>$message) {
            $delayInSeconds = floor($index / $batchSize) * $intervalSeconds;

            SendMessageJob::dispatch($message)->delay($delayInSeconds);

            $this->warn('Dispatched message ID: ' . $message->id . ' with delay of ' . $delayInSeconds . ' seconds');
        }

        $this->info('All messages have been dispatched for sending.');
    }
}
