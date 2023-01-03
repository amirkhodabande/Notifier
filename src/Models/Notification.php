<?php

namespace Amir\Notifier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['channel', 'status', 'provider_url', 'receiver', 'message'];

    protected $casts = ['message' => 'array'];
}