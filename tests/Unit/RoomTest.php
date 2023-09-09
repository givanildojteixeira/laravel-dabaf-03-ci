<?php
namespace Tests\Unit;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

//quando se trabalha com testes em banco de dados
//deve-se fazer algumas modificações como:
//[use Tests\TestCase] no lugar de [use PHPUnit\framework\TestCase]
use Tests\TestCase;

class RoomTest extends TestCase
{
    //inclua esse comando para trabalhar sempre com DataBase limpo
    use RefreshDatabase;

    //nesse teste cria-se um registro na memoria e depois testa para 
    //verificar se ele existe (apenas na memoria)
    public function test_a_room_can_be_created_with_attributes(): void
    {
        $room = new Room(['number' => 2, 'isReserved' => false]);
        
        $this->assertEquals(2, $room->number);
        $this->assertEquals(false, $room->isReserved);
    }

    public function test_a_room_can_be_modified(): void
    {
        $room = new Room(['number' => 2, 'isReserved' => false]);
        $this->assertEquals(false, $room->isReserved);

        $room->isReserved = true;

        $this->assertEquals(true, $room->isReserved);
    }

    public function test_a_room_can_be_persisted(): void
    {
        $room = new Room(['number' => 2, 'isReserved' => false]);
        
        $this->assertCount(0, Room::all());
        
        $room->save();

        $this->assertCount(1, Room::all());
    }
}
