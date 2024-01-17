<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private $mockRepository;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = $this->createMock(UserRepository::class);
        $this->service = new AuthService($this->mockRepository);
    }

    public function test_can_authenticate_user(): void
    {
        $user = new User([
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => 'TestPassword123'
        ]);

        $this->mockRepository->expects($this->once())
            ->method('findByEmail')
            ->willReturn($user);

        $result = $this->service->authenticate('test@user.com', 'TestPassword123');

        $this->assertEquals($user, $result);
    }

    public function test_can_register_new_user(): void
    {
        $user = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => 'TestPassword123',
        ];

        $this->mockRepository->expects($this->once())
            ->method('create')
            ->with($this->equalTo($user))
            ->willReturn(new User($user));

        $result = $this->service->register($user);

        $this->assertInstanceOf(User::class, $result);

        $this->assertEquals($user['name'], $result->name);
        $this->assertEquals($user['email'], $result->email);
        $this->assertTrue(Hash::check($user['password'], $result->password));
    }
}
