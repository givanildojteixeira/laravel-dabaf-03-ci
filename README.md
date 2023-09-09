<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

***
# DABaF - Módulo 3
**:warning: Principais Comandos GIT**
**Inicialização**
- git init     
=>inicia um repositorio
- git remote add origin https://github.com/givanildojteixeira/laravel-dabaf-03-ci.git
=>vincular um repositorio
- git config --global user.name "GivanildoJTeixeira"
=>seta o usuario
- git config --global user.email "givanildo@guara..."
=>seta o email

**Status**
- git status          
=>verifica o Status 
- git config --list
=>lista as configurações

**Commit**
- git add .
=>adiciona todos os arquivos 
- git commit -m "mensagem do commit"
=>prepara o commit
- git push -u origin nome-da-branch
ou depois da primeira
- git push

**Branch**
- git branch bug-123  
=>cria a branch bug-123
- git push origin bug-123  
=>cria e envia essa branch para o repositorio
- git checkout bug-123   
=>Troca para essa branch
ou
- git checkout -b bug-456    
=>cria e troca a branch
- git branch -d bug-123    
=>apaga branch
- git push origin:bug-123  
=>apaga fanch remoto
- git branch    
=>lista as branch


**push**
- git push origin @givanildo/setup-ci
=>efetua o push
git push --set-upstream origin Cria-Readme


:warning: ATENÇÃO:

Quando estiver pronto a merge e quiser atualizar na Main, use o github 
para comparar a unificar a branch, como faz
>git add .    ou git add arquivo
>git commit -m "Comentario"
>git push

No GitHub
Aguarde o teste finalizar dentro da Brach nova na Tag 'Actions'

>Se for automático:

    - clique em commit e merge
    - aguarde os testes 
    - clique em 'Squash and merge'
    - clique em 'Confirm squash and merge'
    - pronto

>Se não for automático

    - vai na brach nova, em 'Code' , clique em 'Contribute' e depois em 'Open pull request'
    - digite um comentario e clique em 'Create pull request'
        Se ocorrer problema de conflitos:
        Decida se você deseja manter apenas as alterações do seu branch, 
        manter apenas as alterações do outro branch, 
        ou fazer uma nova alteração, 
        que pode incorporar alterações de ambos os branches. 
        Exclua os marcadores de conflito <<<<<<<, =======, >>>>>>> 
        e faça as alterações desejadas na mesclagem final.
    e clique em 'Mark as resolved'
    - clique em commit e merge
    - aguarde os testes 
    - clique em 'Squash and merge'
    - clique em 'Confirm squash and merge'
    - pronto
:warning: ATENÇÃO: Se der conflito, é importante fazer um pull para deixar igual
a branch local com a remoto, caso contrario podera ocorrer problemas no prox push

posteriormente pode excluir a branch de teste


![Badge em Desenvolvimento](http://img.shields.io/static/v1?label=STATUS&message=EM%20DESENVOLVIMENTO&color=GREEN&style=for-the-badge)

***
## DABaF - Módulo 4

    Documentação de testes do Laravel:
    https://laravel.com/docs/10.x/testing

**:heavy_check_mark: TESTES UNITARIOS**
comandos no terminal VsCode usados=>
1. sail artisan make:model Room -cmf]      
    * cria a estrutura de testes
>vai criar arquivos em Models -> Room.php
vai criar factories
vai criar migration -> arquivos para migração(alteração) na estrutura do banco de dados entre versoes com a edição de novos campos

2. sail artisan migrate:fresh             
    * reseta o banco e recria com todas as novas tabelas dentro de migrations, sempre andando para frente e nunca faça com dados reais, porque perde dados.

:books: PREPARAÇÃO E ESTUDO : 
            
Como simular no terminal e ja preparar o ambiente para executar os testes

1. Altere o arquivo de migração:
    [arquivo:/database/migrations/2023_08_22_203008_create_rooms_table.php]
```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
    * Run the migrations.
    */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('number');
            $table->boolean('isReserved');
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
```

2. Libere a blindagem do sistema:
arquivo:/app/Models/Room.php
```sh
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // contrato com o sistema quando
    // a factory for criada depois do model
    use HasFactory;

    //quais colunas na tabela podem ser alterados
    protected $fillable = ['number', 'isReserved'];
}
```
3. testes e simulações usando o terminal via tinkr

* sail artisan migrate:fresh     
    * recria o Database
* sail artisan tinker            
    * terminal do laravel 
* namespace App\Models           
    * especifica o model a ser trabalhado
* Room::create(['number' => 1, 'isReserved' => false])   
    * cria registro forçadamente
* Room::all()                    
    * traz os registros

:books:AUTOMATIZAÇÃO E CRIACAO DE TESTES UNITARIOS

Preparação:
* sail artisan make:test RoomTest --unit     
    * cria o arquivo de teste na pasta informada
* sail test tests/Unit/RoomTest.php          
    * para executar um test específico

*na aula foram criados os testes:*
arquivo: /tests/Unit/RoomTest.php
```sh
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
```

*Outro exemplo de test criado usando FACTORY*
Preparação:
arquivo: database/factories/RoomFactory.php
```sh
<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            "number" => fake()->numberBetween(1,100),
            "isReserved" => fake()->boolean()
        ];
    }
}
```
arquivo: /tests/Unit/RoomTest.php     [adicionado]
```sh
public function test_a_room_can_be_generated_by_factory(): void
{
    // make in memory
    $room = Room::factory()->make(); //cria somente na memoria, nao grava no banco
    $this->assertCount(0,Room::all());  //como nao gravou no banco retorna true

    // save to database
    $room2 = Room::factory()->create(); // grava no banco
    $this->assertCount(1,Room::all()); //como gravou no banco retorna true
    
    //criando mais de uma
    $rooms = Room::factory(10)->make();  // grava no banco
    dd($rooms);    //mostra na tela o que foi criado na memoria , durante o teste
}
```
**:heavy_check_mark: TESTES FEATURE**

comandos no terminal usados=>
* sail artisan make:test RoomApiTest         
    * digitar no terminal para criação dos arquivos
* sail test tests/Feature/RoomApiTest.php    
    * executa o test especifico nessa feature

O Exemplo citado pelo professor foi:
Criar uma nova rota chamada '/rooms' que será tratada como api e trará a coleção de objetos gravada no banco, como fazer:

no arquivo >routes>api.php
```sh
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/rooms',[RoomController::class, 'index']);
```
no arquivo Http>controllers>RoomController.php
```sh
class RoomControler extends Controller{
    public function index(){
        return Room::all();
    }
}
```
no arquivo >tests>Feature>RoomApiTest.php
```sh
public function test_api_route_works(): void{
    $response = $this->get('/api/rooms');     //chama a rota

    $response->assertStatus(200);             //verifica se a rota existe
    $response->assertJsonIsArray();           //e se o retorno é a lista populada total
}
```
**:heavy_check_mark: TESTES BROWSER**
**Comandos:**
- sail composer require --dev laravel/dusk    
    - cria o ambiente
- sail php artisan dusk:install              
    -instala e cria uma nova pasta chamada Browser na pasta test
- sail dusk                                   
    - procura e executa os testes de Browser

no arquivo >tests>Browser>ExampleTest.php
```sh
public function test_BasicExample(): void{
    $this->browser(funcion (Browser $browser) {
        $browser->visit('/')
            ->assertSee('robust');
    });
}
```
:warning: ATENÇÃO:

Verifique a documentação porque é possivel preencher formularios no sistema, como login rolar barra e outras automações e depois testar esses elementos simulando um browser



<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).