<?php

namespace App\Repositories;

use App\Models\Card;
use Illuminate\Support\Facades\Cache;

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
        $imageFile = request()->file('image_url');
        unset($data['image_url']);
        $card = $this->card->create($data);

        if ($card && $imageFile) {
            $card->image_url = $imageFile->store('fruits', 'public');
            $card->save();
        }

        return $card;
    }

    public function randomCard()
    {
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

        // Sử dụng cache cho danh sách card theo rarity
        $cacheKey = "cards_rarity_" . $selectedRarity;
        $cardsArr = Cache::get($cacheKey);

        if ($cardsArr) {
            info("Lấy cards từ Redis cache: " . $cacheKey);
            $cards = collect($cardsArr)->map(function ($item) {
                return new Card($item);
            });
        } else {
            info("Lấy cards từ database và lưu vào Redis cache: " . $cacheKey);
            $cards = $this->card->where('rarity', $selectedRarity)->get();
            Cache::put($cacheKey, $cards->toArray(), 60); // TTL 60 giây
        }

        if ($cards->isEmpty()) {
            $allCacheKey = 'cards_all_random';
            $randomCardArr = Cache::get($allCacheKey);
            if ($randomCardArr) {
                info("Lấy card random từ Redis cache: " . $allCacheKey);
                return new Card($randomCardArr);
            } else {
                info("Lấy card random từ database và lưu vào Redis cache: " . $allCacheKey);
                $randomCard = $this->card->inRandomOrder()->first();
                Cache::put($allCacheKey, $randomCard->toArray(), 60);
                return $randomCard;
            }
        }

        // Lặp random cho đến khi chọn được card mà chỉ có 1 card cùng name trong nhóm
        $attempt = 0;
        do {
            $randomCard = $cards->random();
            $sameNameCards = $cards->where('name', $randomCard->name);
            $attempt++;
            // Nếu chỉ có 1 card cùng name hoặc đã thử quá 10 lần thì trả về card này
            if ($sameNameCards->count() === 1 || $attempt > 10) {
                return $randomCard;
            }
            // Nếu còn nhiều card cùng name, random tiếp trong nhóm đó
            $cards = $sameNameCards;
        } while (true);
    }
}
