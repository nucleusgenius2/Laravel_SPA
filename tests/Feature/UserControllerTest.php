<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Http\Controllers\UserController;
use Mockery\MockInterface;


class UserControllerTest extends TestCase
{
    //use RefreshDatabase;


    public function test_isAdminPermission(): bool
    {
        $user = User::factory()->create();

        $mock = $this->mock(UserController::class, function (MockInterface $mock) {
            $mock->shouldReceive('isAdminPermission')->once();
        });

        $responsive = app(UserController::class)->isAdminPermission($user);
        if ( $responsive == false ){ return true; }
        else { return false; }
    }


    public function test_registrationUser(): void
    {
        $deleteTestUser = User::where('email', '=', 'test@example.com')->delete();

        $response = $this->post('/api/registration', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('status', 'success')
                ->etc()
            );
    }

    public function test_loginUser(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('status', 'success')
                ->etc()
            );
    }


}

