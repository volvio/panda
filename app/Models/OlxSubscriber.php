<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OlxSubscriber extends Model
{
    use HasFactory;

    protected $table = 'olx_subscribers';

    protected $fillable = [
        'olx_link_id',
        'email',
        'confirmed_at',
        'confirmation_token',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function olxLink()
    {
        return $this->belongsTo(OlxLink::class, 'olx_link_id');
    }
}
