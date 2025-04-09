<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_list_fetched(): void
    {
        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200)
        ->assetj;
    }
}
