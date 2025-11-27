<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name',
        'stars',
        'description',
        'rarity',
        'image_url',
    ];

    public function userCards()
    {
        return $this->hasMany(UserCard::class);
    }

    public function tradeItems()
    {
        return $this->hasMany(TradeItem::class);
    }
}
