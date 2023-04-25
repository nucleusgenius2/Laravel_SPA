<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class PostControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_getPostSingle(): void
    {
        $response = $this->getJson('/api/post/1');

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('status', 'success')
                     ->etc()
            );
    }


    public function test_getPostList(): void
    {
        $response = $this->getJson('/api/post-list/1');

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('status', 'success')
                //->where('content', 'Victoria Faith')
                // ->missing('password')
                 ->etc()
            );
    }


}
