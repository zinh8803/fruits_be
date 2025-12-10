<?php

namespace App\Repositories;

use App\Models\Card;
use App\Models\UserCard;
use Illuminate\Support\Facades\Auth;

class CardRepository
{
    protected $card;
    protected $userCard;

    public function __construct(Card $card, UserCard $userCard)
    {
        $this->card = $card;
        $this->userCard = $userCard;
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
        $imageFile = request()->file('image_url');
        unset($data['image_url']);
        $card = $this->card->create($data);

        if ($card && $imageFile) {
            $card->image_url = $imageFile->store('fruits', 'public');
            $card->save();
        }

        return $card;
    }


    public function updateCard($id, $data)
    {
        $card = $this->getCardById($id);
        if (!$card) {
            return null;
        }

        $imageFile = request()->file('image_url');
        unset($data['image_url']);
        $card->update($data);

        if ($imageFile) {
            $card->image_url = $imageFile->store('fruits', 'public');
            $card->save();
        }

        return $card;
    }

    public function deleteCard($id)
    {
        $card = $this->getCardById($id);
        if (!$card) {
            return false;
        }

        return $card->delete();
    }

    public function userCards($user_id)
    {
        return $this->userCard->where('user_id', $user_id)->with('card')->get();
    }

    public function randomCard()
    {
        $user_id = Auth::id();
        // Định nghĩa tỷ lệ rarity (có thể điều chỉnh theo nhu cầu)
        $rarityRates = [
            'common' => 60,
            'rare' => 30,
            'epic' => 9,
            'legendary' => 1,
        ];

        // Tạo mảng pool theo tỷ lệ
        $pool = [];
        foreach ($rarityRates as $rarity => $rate) {
            for ($i = 0; $i < $rate; $i++) {
                $pool[] = $rarity;
            }
        }

        // Random rarity theo tỷ lệ
        $selectedRarity = $pool[array_rand($pool)];

        // Lấy các card thuộc rarity đã chọn
        $cards = $this->card->where('rarity', $selectedRarity)->get();

        if ($cards->isEmpty()) {
            // Nếu không có card thuộc rarity này, fallback random bất kỳ
            return $this->card->inRandomOrder()->first();
        }

        // Lặp random cho đến khi chọn được card mà chỉ có 1 card cùng name trong nhóm
        $attempt = 0;
        do {
            $randomCard = $cards->random();
            $sameNameCards = $cards->where('name', $randomCard->name);
            $attempt++;
            // Nếu chỉ có 1 card cùng name hoặc đã thử quá 10 lần thì chọn card này
            if ($sameNameCards->count() === 1 || $attempt > 10) {
                // Sau khi random xong thì +1 số lượng card cho user
                if ($user_id) {
                    $userCard = $this->userCard
                        ->where('user_id', $user_id)
                        ->where('card_id', $randomCard->id)
                        ->first();

                    if ($userCard) {
                        $userCard->quantity += 1;
                        $userCard->save();
                    } else {
                        $this->userCard->create([
                            'user_id'  => $user_id,
                            'card_id'  => $randomCard->id,
                            'quantity' => 1,
                        ]);
                    }
                }

                return $randomCard;
            }
            // Nếu còn nhiều card cùng name, random tiếp trong nhóm đó
            $cards = $sameNameCards;
        } while (true);
    }
}
