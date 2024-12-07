<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function tearDown(): void
    {
        User::where('email', 'like', '%@example.com')->delete();

        parent::tearDown();
    }
    public function test_register_creates_a_user(): void
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'name' => $faker->name,
            'email' => 'bruno@example.com',
            'phone' => '12345678911',
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',
        ];

        $response = $this->postJson('/api/create', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);


        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ],
        ]);
    }

    public function test_register_with_email_already_exists(): void
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'name' => $faker->name,
            'email' => 'admin@gmail.com',
            'phone' => $faker->phoneNumber,
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',

        ];

        $response = $this->postJson('api/create', $payload);


        $response->assertStatus(422);
    }

    public function test_register_with_phone_already_exists(): void
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'name' => $faker->name,
            'email' => $faker->email,
            'phone' => "(51) 38211-4122",
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',

        ];

        $response = $this->postJson('api/create', $payload);


        $response->assertStatus(422);
    }

    public function test_register_with_password_not_matching_confirmation(): void
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'name' => $faker->name,
            'email' => $faker->email,
            'phone' => "(51) 38211-4122",
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaErrada456',
        ];

        $response = $this->postJson('api/create', $payload);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['password']);
    }

    public function test_register_with_missing_required_fields(): void
    {
        $payload = [
            'name' => '',
            'email' => 'bruno@example.com',
            'phone' => '12345678911',
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',
        ];

        $response = $this->postJson('api/create', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_register_with_short_password(): void
    {
        $payload = [
            'name' => 'Bruno',
            'email' => 'bruno@example.com',
            'phone' => '12345678911',
            'password' => '123',
            'password_confirmation' => '123',
        ];

        $response = $this->postJson('api/create', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_login(): void
    {
        $payload = [
            'email' => 'admin@gmail.com',
            'password' => 'SenhaForte123',
        ];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'cpf',
                    'email_verified_at',
                    'verification_code',
                    'phone',
                    'address',
                    'city',
                    'state',
                    'country',
                    'cep',
                    'birth_date',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ],
        ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $payload = [
            'email' => 'bruno@example.com',
            'password' => 'SenhaErrada123',
        ];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'message' => 'Usuário não encontrado',
        ]);
    }

    public function test_login_with_empty_password(): void
    {
        $payload = [
            'email' => 'admin@gmail.com',
            'password' => '',  // Senha vazia
        ];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_logout(): void
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'name' => $faker->name,
            'email' => 'bruno@example.com',
            'phone' => '12345678911',
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',
        ];

        $response = $this->postJson('api/create', $payload);

        $token =  $response['data']['token'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');


        $response->assertStatus(200);

        $response->assertJson([
            "status" => 200,
            "message" => "Logout realizado com sucesso",
            "data" => []
        ]);
    }

    public function test_logout_without_authentication(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);

        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }
}
