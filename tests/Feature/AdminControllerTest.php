<?php

use App\Http\Controllers\Admin\AdminController;
use App\Models\Permission;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;


class AdminControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function test_createPost(): void
    {
        $response = $this->post('/api/admin/create', [
            'name' => 'Test post',
            'content' => 'text test text test text test',
            'short_description' => 'text test',
            'seo_title' => 'text test',
            'seo_description' => 'text test',
            'img' => null,
            'id_category' => '1',
            'author' => 'testpost@mail.tu',
        ]);

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('status', 'success')
                ->etc()
            );
    }

    public function test_updatePost(): void
    {
        $response = $this->post('/api/admin/update', [
            'id' => '1',
            'name' => 'Test post update',
            'content' => 'text test text test text test update',
            'short_description' => 'text test update',
            'seo_title' => 'text test',
            'seo_description' => 'text test',
            'img' => '',
            'id_category' => '1',
            'author' => 'testpost@mail.tu',
        ]);

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('status', 'success')
                ->etc()
            );
    }

    public function test_deletePost(): void
    {
        $idPostLast = Post::orderBy('id', 'desc')->first()->id;

        $response = $this->delete('/api/admin/delete/'.$idPostLast);

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('status', 'success')
                ->etc()
            );
    }


}
