<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeItem extends Model
{
    protected $table = 'trade_item';

    protected $fillable = [
        'trade_id',
        'card_id',
        'quantity',
    ];

    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
