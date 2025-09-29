<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OlxPrice extends Model
{
    use HasFactory;

    protected $table = 'olx_prices';

    protected $fillable = [
        'olx_link_id',
        'price',
        'currency',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function olxLink()
    {
        return $this->belongsTo(OlxLink::class, 'olx_link_id');
    }
}
