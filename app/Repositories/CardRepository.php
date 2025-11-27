<?php

namespace App\Repositories;

use App\Models\Card;

class CardRepository
{
    protected $card;
    public function __construct(Card $card)
    {
        $this->card = $card;
    }
    public function getAllCards()
    {
        return $this->card->all();
    }

    public function getCardById($id)
    {
        return $this->card->find($id);
    }

    public function createCard($data)
    {
        $imageFile = $data['image_url'] ?? null;
        unset($data['image_url']);
        $card = $this->card->create($data);

        if ($card && $imageFile && is_file($imageFile)) {
            $card->image_url = $imageFile->store('fruits', 'public');
            $card->save();
        }

        return $card;
    }
}
