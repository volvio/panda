<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OlxLink extends Model
{
    use HasFactory;

    protected $table = 'olx_links';

    protected $fillable = [
        'url',
        'olx_id',
        'is_price_update',
    ];

    protected $casts = [
        'is_price_update' => 'boolean',
        'olx_id' => 'integer',
    ];

    public function subscribers()
    {
        return $this->hasMany(OlxSubscriber::class, 'olx_link_id');
    }

    public function prices()
    {
        return $this->hasMany(OlxPrice::class, 'olx_link_id');
    }

    public function latestPrice()
    {
        return $this->hasOne(OlxPrice::class, 'olx_link_id')->latestOfMany();
    }
}
