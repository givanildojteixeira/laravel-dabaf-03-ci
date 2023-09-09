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
        //cria a variavel
        $room = new Room(['number' => 2, 'isReserved' => false]);
        //faz uma contagem que deve resultar = 0 porque estamos
        //trabalhando com o banco de dados em branco, 
        //devido ao comando use RefreshDatabase;
        //e nossa gravação esta somene em memória
        $this->assertCount(0, Room::all());
        //agora se salvar no banco 
        //e
        //para isso devemos mudar para use Tests\TestCase;
        //senao da problema de conexao
        $room->save();
        //depois de salvo deve resultar em um reg criado
        $this->assertCount(1, Room::all());
    }
    public function test_a_room_can_be_generated_by_factory(): void
{
    // make in memory
    $room = Room::factory()->make(); //cria somente na memoria, nao grava no banco
    $this->assertCount(0,Room::all());  //como nao gravou no banco retorna true

    // save to database
    $room2 = Room::factory()->create(); // grava no banco
    $this->assertCount(1,Room::all()); //como gravou no banco retorna true
    
    //criando mais de uma
    //$rooms = Room::factory(10)->make();  // grava no banco
    //dd($rooms);    //mostra na tela o que foi criado na memoria , durante o teste
}
}
