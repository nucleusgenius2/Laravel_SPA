<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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

    public function test_api(): void
    {
        //$response = $this->get('/api/post/1');
        //log::info( json_encode($response) );
       // $this->assertInstanceOf( JsonResponse::class, $response);
        //$response->assertStatus(200);


      /* $response = $this->getJson('/api/post/1');
        log::info( json_encode($response) );
        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('id', 1)
                ->where('content', 'Victoria Faith')
                ->missing('password')
                ->etc()
            );
      */

         $response = $this->getJson('/api/post/1');
        $response
            ->assertJson(

                fn (AssertableJson $json) =>
            $json->has(1)
                ->first(fn ($json) =>
                $json->where('id', 1)
                    ->where('name', 'News One')
                    ->missing('password')
                    ->etc()
                )
            );

    }

    public function test_getPostSingle(): void
    {
        $id = 1;
        $response = $this->getPostSingle($id);

        $this->assertInstanceOf(Post::class, $response);
        //$this->assertInstanceOf(User::class, $response);
    }


}
