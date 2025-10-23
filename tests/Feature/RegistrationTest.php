<?php

use App\Models\User;

require_once __DIR__ . '/../TestCase.php';

class RegistrationTest extends TestCase
{
    public function testUserRegistrationPersistsWithHash(): void
    {
        /** @var User $users */
        $users = $this->container->make(User::class);
        $userId = $users->createFromRegistration([
            'name' => 'Novo Membro',
            'email' => 'novo@example.com',
            'phone' => '+244900000111',
            'password' => password_hash('Segredo123', PASSWORD_BCRYPT),
            'gender' => 'm',
            'is_adventist' => 1,
            'country' => 'Angola',
            'province' => 'Luanda',
            'municipio' => 'Talatona',
        ]);

        $this->assertTrue($userId > 0, 'User ID should be generated.');
        $stored = $users->find($userId);
        $this->assertEquals('Novo Membro', $stored['name']);
        $this->assertTrue(password_verify('Segredo123', $stored['password']));
    }
}
