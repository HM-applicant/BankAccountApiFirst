<?php

namespace Tests\Feature;

use App\Transaction\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiImportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFailsApiImportMethod()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', '/api/transaction/import/1/10-10-2019');

        $response->assertStatus(405);
    }

    public function testFailsApiImportResource()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/transaction/import/666/10-10-2019');

        $response->assertStatus(404);
    }

    public function testSuccessApiImport()
    {
        $user = factory(User::class)->create();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/transaction/import/' . $user->getId() . '/10-10-2019');

        $response->assertStatus(200);
    }
}
