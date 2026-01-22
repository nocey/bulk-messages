<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'phone',
        'content',
        'status',
        'message_id',
        'created_at',
        'updated_at',
    ];
    
    public const MAX_CONTENT_LENGTH = 200; 
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_PENDING = 'pending';
}
