<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class MessageApiTest extends TestCase
{

    use RefreshDatabase; 

    public function test_can_retrieve_sent_messages(): void
    {
        Message::create([
            'phone' => '779.978.3048',
            'content' => 'Test Message',
            'status' => 'sent',
            'created_at' => now(),
            'updated_at'=> now(),
            'message_id' => 'test-id-123'
        ]);

        $response = $this->get('/api/sent-messages');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         '*' => ['id', 'content', 'status', 'message_id'] // Dönmesi gereken alanlar
                     ]
                 ]);
    }
}
