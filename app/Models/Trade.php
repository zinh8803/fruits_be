<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = [
        'offering_user_id',
        'receiving_user_id',
        'offered_card_accepted',
        'received_card_accepted',
    ];

    public function tradeItems()
    {
        return $this->hasMany(TradeItem::class);
    }

    public function offeringUser()
    {
        return $this->belongsTo(User::class, 'offering_user_id');
    }
    public function receivingUser()
    {
        return $this->belongsTo(User::class, 'receiving_user_id');
    }
}
