<?php

namespace Tests\Feature;

use App\Models\User; // Pastikan Anda mengimpor model User
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker; // Mungkin tidak terlalu dibutuhkan untuk test ini
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase; // Gunakan ini jika Anda membuat user di database.
                         // Atau jika Anda hanya membuat instance tanpa menyimpan, ini tidak wajib.

    /** @test */
    public function is_admin_returns_true_when_user_role_is_admin(): void
    {
        // Cara 1: Membuat instance model langsung (tanpa database)
        $adminUser = new User(['role' => 'admin']);

        // Cara 2: Menggunakan factory untuk membuat instance (tanpa database)
        // $adminUser = User::factory()->make(['role' => 'admin']);

        // Cara 3: Menggunakan factory untuk membuat dan menyimpan ke database (butuh RefreshDatabase)
        // $adminUser = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($adminUser->isAdmin(), 'isAdmin() should return true for admin role.');
        $this->assertFalse($adminUser->isMahasiswa(), 'isMahasiswa() should return false for admin role.');
    }

    /** @test */
    public function is_admin_returns_false_when_user_role_is_not_admin(): void
    {
        $mahasiswaUser = new User(['role' => 'mahasiswa']);
        // $mahasiswaUser = User::factory()->make(['role' => 'mahasiswa']);

        $this->assertFalse($mahasiswaUser->isAdmin(), 'isAdmin() should return false for non-admin role.');
    }

    /** @test */
    public function is_mahasiswa_returns_true_when_user_role_is_mahasiswa(): void
    {
        $mahasiswaUser = new User(['role' => 'mahasiswa']);
        // $mahasiswaUser = User::factory()->make(['role' => 'mahasiswa']);

        $this->assertTrue($mahasiswaUser->isMahasiswa(), 'isMahasiswa() should return true for mahasiswa role.');
        $this->assertFalse($mahasiswaUser->isAdmin(), 'isAdmin() should return false for mahasiswa role.');
    }

    /** @test */
    public function is_mahasiswa_returns_false_when_user_role_is_not_mahasiswa(): void
    {
        $adminUser = new User(['role' => 'admin']);
        // $adminUser = User::factory()->make(['role' => 'admin']);

        $this->assertFalse($adminUser->isMahasiswa(), 'isMahasiswa() should return false for non-mahasiswa role.');
    }

    /** @test */
    public function role_methods_return_false_for_undefined_role(): void
    {
        // Menguji dengan peran yang tidak 'admin' atau 'mahasiswa'
        $otherUser = new User(['role' => 'dosen']); // Misalnya, peran lain
        // $otherUser = User::factory()->make(['role' => 'dosen']);

        $this->assertFalse($otherUser->isAdmin(), 'isAdmin() should return false for other roles.');
        $this->assertFalse($otherUser->isMahasiswa(), 'isMahasiswa() should return false for other roles.');
    }

    /** @test */
    public function role_methods_return_false_if_role_is_null(): void
    {
        // Menguji jika atribut role null
        $userWithNullRole = new User(['role' => null]);
        // $userWithNullRole = User::factory()->make(['role' => null]);

        $this->assertFalse($userWithNullRole->isAdmin(), 'isAdmin() should return false if role is null.');
        $this->assertFalse($userWithNullRole->isMahasiswa(), 'isMahasiswa() should return false if role is null.');
    }
}