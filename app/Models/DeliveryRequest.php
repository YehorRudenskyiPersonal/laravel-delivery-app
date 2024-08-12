<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_name',
        'user_email',
        'request_content',
        'response_status'
    ];

    protected $casts = [
        'request_content' => 'array',
    ];
}
