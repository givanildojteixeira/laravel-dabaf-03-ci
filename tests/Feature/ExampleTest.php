<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_text_quando_contem(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Documentation');
    }

    public function test_text_quando_nao_contem()
{
    $response = $this->get('/');

    // Verifica se a resposta não contém o texto "Texto indesejado".
    $response->assertDontSee('Texto indesejado');
}


}

