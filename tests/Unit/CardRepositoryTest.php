<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Card;
use App\Models\UserCard;
use App\Repositories\CardRepository;

class CardRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $cardRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cardRepository = new CardRepository(new Card(), new UserCard());
    }

    public function test_create_card()
    {
        $data = [
            'name' => 'Test Fruit',
            'stars' => 3,
            'description' => 'Test description',
            'rarity' => 'rare',
        ];
        $card = $this->cardRepository->createCard($data);
        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals('Test Fruit', $card->name);
    }

    public function test_get_all_cards()
    {
        Card::factory()->count(2)->create();
        $cards = $this->cardRepository->getAllCards();
        $this->assertCount(2, $cards);
    }

    public function test_get_card_by_id()
    {
        $card = Card::factory()->create(['name' => 'Unique Fruit']);
        $found = $this->cardRepository->getCardById($card->id);
        $this->assertEquals('Unique Fruit', $found->name);
    }

    public function test_update_card()
    {
        $card = Card::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];
        $updated = $this->cardRepository->updateCard($card->id, $data);
        $this->assertEquals('New Name', $updated->name);
    }

    public function test_delete_card()
    {
        $card = Card::factory()->create();
        $result = $this->cardRepository->deleteCard($card->id);
        $this->assertTrue($result);
        $this->assertNull(Card::find($card->id));
    }
}
