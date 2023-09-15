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
# DABaF - Módulo 4

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


***
# DABaF - Módulo 5 - Pipeline CD com CapRover e Webhooks
Ideia: Prover um mecanismo que integre desde o seu repositório de código até o servidor, onde está hospedado a aplicação automatizando 
esse processo de modo que os testes sejam executados e, se passarem, executam um pipeline para enviar o código para o servidor de produção.
Existem varias infraestruturas que provem esse contrato, na aula foi apresentado uma usando:
Github - CapRover - Servidor com docker

## Configurando o Servidor para hospedar uma configuração Laravel
Usado o Digital Ocean como exemplo
necessita uma máquina virtual ativa na internet e um domínio válido
### Criação da máquina virtual
Atenção, verifique a capacidade da máquina, capacidade de transferência, armazenamento, processamento, sistema operacional, e suporte para caso de necessidade
busque sempre os tutoriais da empresa que está fornecendo a máquina virtual, pois eles estarão atualizados!
### Acertos de firewall
Proteja a infra, liberando tudo o que sai e bloqueando o que entra, exceto o ssh, para não perder a conexão!
Comandos: (Linux)

    sudo ufw allow ssh                 // libera conexoes de entrada via ssh
    sudo ufw default deny incoming     // bloqueia todas as portas de entrada
    sudo ufw default allow outgoing    // libera tudo o que tiver saindo
    sudo ufw enable                    // ativa o firewall
    sudo ufw status                    // para ver o status


## CapRover
É um conteiner Docker que roda no servidor e a partir dele é possivel orquestrar outros conteiners
Ele fica no servidor, aguardando chamadas do GitHub quando ocorre deployer novos.
Ele possui acessos tanto ao GutHub quando ao servidor e sua função é pegar esses novos códigos, 
montar a imagem e implantar no ambiente de produção.
É um código open source!
Para funcionar é necessário:
Configurar o Domínio, instalar o Docker e o Docker Composite

### Configure o DNS: (dominio)
Em networking: crie um subsdominio ex: *.laravel e aponte para o seu IP do servidor,
aguarde a propagação e teste para ver se já é possível conectar
Comando usado para conexão:

    ssh root@caprover.laravel.debug.app.br     (agora usando DNS ao inves de IP)

*Bonus: no Dnschecker voce pode verificar seu dominio*

### 1. Instale o Docker  (Engine)
Atenção: Lembre-se de estar acessando o servidor
Acesse: https://docs.docker.com/engine/install/ubuntu/
        e digite os comandos:

#### Add Docker's official GPG key:
    sudo apt-get update
    sudo apt-get install ca-certificates curl gnupg
    sudo install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    sudo chmod a+r /etc/apt/keyrings/docker.gpg

####  Add the repository to Apt sources:
    echo \
        "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
       $(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
    sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
    sudo apt-get update

####  Instalar última versão
    sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

#### Configure Docker to start on boot with systemd 
    sudo systemctl enable docker.service
    sudo systemctl enable containerd.service

#### Configure Firewall
Libere as portas do firewall

    ufw allow 80,443,3000,996,7946,4789,2377/tcp; ufw allow 7946,4789,2377/udp;

### 2. Instale o CapRover
    
    docker run -p 80:80 -p 443:443 -p 3000:3000 -e ACCEPTED_TERMS=true -v /var/run/docker.sock:/var/run/docker.sock -v /captain:/captain caprover/caprover

#### Acesse o CapRover
http://[IP_OF_YOUR_SERVER]:3000
password: captain42
em Dashboard > [wildcard.]
indique para ele qual o domínio que ele está ex: laravel.debug.app.br
isso te dará acesso diretamente via domínio e não mais pela porta
habilite o modo Seguro Https

### 2. Configure o GitHub
##### Criação do .deploy
Template para deploy do Laravel com CapRover:
https://github.com/jackbrycesmith/laravel-caprover-template 
Baixe esse repositório, coloque na raiz do codigo, assim, você terá uma estrutura assim:
>Config
    >Dockerfile

    ARG PHP_VERSION=${PHP_VERSION:-7.4}
    FROM php:${PHP_VERSION}-fpm-alpine AS php-system-setup
    
    # Install system dependencies
    RUN apk add --no-cache dcron busybox-suid libcap curl zip unzip git
    
    # Install PHP extensions
    COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
    RUN install-php-extensions intl bcmath gd pdo_mysql pdo_pgsql opcache redis uuid exif pcntl zip
    
    # Install supervisord implementation
    COPY --from=ochinchina/supervisord:latest /usr/local/bin/supervisord /usr/local/bin/supervisord
    
    # Install caddy
    COPY --from=caddy:2.2.1 /usr/bin/caddy /usr/local/bin/caddy
    RUN setcap 'cap_net_bind_service=+ep' /usr/local/bin/caddy
    
    # Install composer
    COPY --from=composer/composer:2 /usr/bin/composer /usr/local/bin/composer
    
    FROM php-system-setup AS app-setup
    
    # Set working directory
    ENV LARAVEL_PATH=/srv/app
    WORKDIR $LARAVEL_PATH
    
    # Add non-root user: 'app'
    ARG NON_ROOT_GROUP=${NON_ROOT_GROUP:-app}
    ARG NON_ROOT_USER=${NON_ROOT_USER:-app}
    RUN addgroup -S $NON_ROOT_GROUP && adduser -S $NON_ROOT_USER -G $NON_ROOT_GROUP
    RUN addgroup $NON_ROOT_USER wheel
    
    # Set cron job
    COPY ./.deploy/config/crontab /etc/crontabs/$NON_ROOT_USER
    RUN chmod 777 /usr/sbin/crond
    RUN chown -R $NON_ROOT_USER:$NON_ROOT_GROUP /etc/crontabs/$NON_ROOT_USER && setcap cap_setgid=ep /usr/sbin/crond
    
    # Switch to non-root 'app' user & install app dependencies
    COPY composer.json composer.lock ./
    RUN chown -R $NON_ROOT_USER:$NON_ROOT_GROUP $LARAVEL_PATH
    USER $NON_ROOT_USER
    RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader
    RUN rm -rf /home/$NON_ROOT_USER/.composer
    
    # Copy app
    COPY --chown=$NON_ROOT_USER:$NON_ROOT_GROUP . $LARAVEL_PATH/
    COPY ./.deploy/config/php/local.ini /usr/local/etc/php/conf.d/local.ini
    
    # Set any ENVs
    ARG APP_KEY=${APP_KEY}
    ARG APP_NAME=${APP_NAME}
    ARG APP_URL=${APP_URL}
    ARG APP_ENV=${APP_ENV}
    ARG APP_DEBUG=${APP_DEBUG}
    
    ARG LOG_CHANNEL=${LOG_CHANNEL}
    
    ARG DB_CONNECTION=${DB_CONNECTION}
    ARG DB_HOST=${DB_HOST}
    ARG DB_PORT=${DB_PORT}
    ARG DB_DATABASE=${DB_DATABASE}
    ARG DB_USERNAME=${DB_USERNAME}
    ARG DB_PASSWORD=${DB_PASSWORD}
    
    ARG BROADCAST_DRIVER=${BROADCAST_DRIVER}
    ARG CACHE_DRIVER=${CACHE_DRIVER}
    ARG QUEUE_CONNECTION=${QUEUE_CONNECTION}
    ARG SESSION_DRIVER=${SESSION_DRIVER}
    ARG SESSION_LIFETIME=${SESSION_LIFETIME}
    
    ARG REDIS_HOST=${REDIS_HOST}
    ARG REDIS_PASSWORD=${REDIS_PASSWORD}
    ARG REDIS_PORT=${REDIS_PORT}
    
    ARG MAIL_MAILER=${MAIL_MAILER}
    ARG MAIL_HOST=${MAIL_HOST}
    ARG MAIL_PORT=${MAIL_PORT}
    ARG MAIL_USERNAME=${MAIL_USERNAME}
    ARG MAIL_PASSWORD=${MAIL_PASSWORD}
    ARG MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
    ARG MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
    ARG MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
    ARG MAIL_FROM_NAME=${APP_NAME}
    
    ARG PUSHER_APP_ID=${PUSHER_APP_ID}
    ARG PUSHER_APP_KEY=${PUSHER_APP_KEY}
    ARG PUSHER_APP_SECRET=${PUSHER_APP_SECRET}
    ARG PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}
    
    # Start app
    EXPOSE 80
    COPY ./.deploy/entrypoint.sh /
    
    ENTRYPOINT ["sh", "/entrypoint.sh"]

Atenção com as variáveis de ambientes, que são criadas, ou seja, devem ser criadas também nesse arquivo

##### Para sincronizar github <> CapRover
1. No CapRover, crie uma aplicação em [Apps]

2. Acesse e copie o conteúdo do arquivo  .env em [App Configs] [Bulk Edit]

>Atente a geração de uma chave APP_Key diferente
    e APP_URL= mude para o local onde vai rodar a aplicação ex: web.laravel.debug.app.br
    Alterações do MYSQL.
    Na Aba Deployment, que o local que será configurado como o sistema vai integrar

3. Usamos o método 3, onde a integração é via GitHub:

- tem o repositorio: https://github.com/givanildojteixeira/laravel-dabaf-03-ci
- a Branch: Production - é a que será usada como código para produção
- o Local onde está o arquivo: captain-definition: /captain-definition
- a chave SSH (privada)

##### Criando uma chave privada
Em uma pasta vazia de terminal, digite:

    ssh-keygen -t rsa -m PEM

- Coloque um nome e uma senha
- Coloque a chave pub(publica) no github, e

A chave privada no cliente (servidor)(CapRover)

4. E uma url que será colocada no WebHooks para melhorar o sincronismo:

Para o sincronismo entre o github e o CapRover, adicione a URL na aba Webhooks do github, assim, quando realizar um commit na branch de produção o CapRover será avisado e a imagem do sistema será implantanda no servidor automaticamente

Nesse ponto já deve estar funcionando o Pipeline, Porém ainda resta criar o Banco de Dados e configurar para que o sistema funcione adequadamente!

##### Criando o Banco de Dados
Em Apps na configuração do CapRover:
- Clique em [One-Click Apps/DataBases]
- Pesquise epor MySQL.
- Coloque nome, Versão e Senha.
- Altere essas informações em Apps > Web > App config
    db_hosts
    db_username
    db_password

Atenção: Conecte a esse banco e crie as tabelas para não dar erro nesse processo!
Em Ambiente real, esse banco pode ser feito upload com sua estrutura e informações.

Em resumo:
1. Tendo um repositorio GitHub, com o codigo fonte de sua aplicação,
2. Tendo um servidor ativo, com Ip válido, ao qual é possivel conectar ele via SSH,
3. Este servidor ter o Docker instalado e um container usando a imagem do CapRover,
4. No CapRover tere uma aplicação com o método deploy e apontado para um repositório github, com uma URL webhook,
5. Basta realizar um commit na branch de produção, e o restante será automatizado no servidor de produção

***
***
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
