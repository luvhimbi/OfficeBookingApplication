<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    #[Test]
    public function it_can_login_a_user_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $credentials = ['email' => $user->email, 'password' => 'secret123'];

        $loggedInUser = $this->authService->login($credentials);

        $this->assertNotNull($loggedInUser);
        $this->assertEquals($user->id, $loggedInUser->id);
        $this->assertTrue(Auth::check());
    }

    #[Test]
    public function it_returns_null_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $credentials = ['email' => $user->email, 'password' => 'wrongpassword'];

        $loggedInUser = $this->authService->login($credentials);

        $this->assertNull($loggedInUser);
        $this->assertFalse(Auth::check());
    }

    #[Test]
    public function it_can_logout_a_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $this->authService->logout();

        $this->assertFalse(Auth::check());
    }

    #[Test]
    public function it_can_update_profile_and_hash_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpass'),
        ]);

        $data = [
            'firstname' => 'NewFirst',
            'lastname' => 'NewLast',
            'email' => 'newemail@example.com',
            'password' => 'newpass123',
        ];

        $updatedUser = $this->authService->updateProfile($user, $data);

        $this->assertEquals('NewFirst', $updatedUser->firstname);
        $this->assertEquals('NewLast', $updatedUser->lastname);
        $this->assertEquals('newemail@example.com', $updatedUser->email);
        $this->assertTrue(Hash::check('newpass123', $updatedUser->password));
    }

    #[Test]
    public function it_can_toggle_two_factor_authentication()
    {
        $user = User::factory()->create(['two_factor_enabled' => false]);

        // First toggle
        $status = $this->authService->toggleTwoFactor($user);
        $this->assertTrue($status);

        // Check database directly
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'two_factor_enabled' => true,
        ]);

        // Second toggle
        $status = $this->authService->toggleTwoFactor($user);
        $this->assertFalse($status);

        // Check database directly again
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'two_factor_enabled' => false,
        ]);
    }

}
