<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Services\MessageServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_message_content_length_validation(): void
    {
        $repositoryMock = $this->createMock(MessageRepository::class);

        $service = new MessageServices($repositoryMock);

        $validContent = "this is a valid message content.";
        $this->assertTrue($service->validateMessageContent($validContent));

        $invalidContent = str_repeat("A", Message::MAX_CONTENT_LENGTH + 1); 
        $this->assertFalse($service->validateMessageContent($invalidContent));
    }
}
