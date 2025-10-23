<?php

namespace Database\Seeders;

use App\Core\Database\Connection;
use PDO;

class DatabaseSeeder
{
    private string $driver;

    public function __construct(private readonly Connection $connection)
    {
        $this->driver = $this->connection->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    public function run(): void
    {
        $pdo = $this->connection->getPdo();
        $this->seedRegions($pdo);
        $this->seedChurchRoles($pdo);
        $this->seedAdmin($pdo);
    }

    private function seedRegions(PDO $pdo): void
    {
        if ($this->driver === 'sqlite') {
            $pdo->exec("INSERT OR REPLACE INTO regions (id, name, type) VALUES (1, 'Região Luanda', 'region')");
            $pdo->exec("INSERT OR REPLACE INTO districts (id, region_id, name) VALUES (1, 1, 'Distrito Central')");
            $pdo->exec("INSERT OR REPLACE INTO churches (id, district_id, name) VALUES (1, 1, 'Igreja Central de Luanda')");
        } else {
            $pdo->exec("INSERT INTO regions (id, name, type) VALUES (1, 'Região Luanda', 'region') ON DUPLICATE KEY UPDATE name = VALUES(name)");
            $pdo->exec("INSERT INTO districts (id, region_id, name) VALUES (1, 1, 'Distrito Central') ON DUPLICATE KEY UPDATE name = VALUES(name)");
            $pdo->exec("INSERT INTO churches (id, district_id, name) VALUES (1, 1, 'Igreja Central de Luanda') ON DUPLICATE KEY UPDATE name = VALUES(name)");
        }
    }

    private function seedChurchRoles(PDO $pdo): void
    {
        $roles = [
            ['Presidente', 100, 'association'],
            ['Pastor', 90, 'district'],
            ['Ancião', 80, 'church'],
            ['Diretor Lar & Família', 70, 'church'],
            ['Diretor Ministério', 70, 'church'],
        ];
        $sql = $this->driver === 'sqlite'
            ? 'INSERT OR REPLACE INTO church_roles (id, name, hierarchy_level, scope, created_at) VALUES ((SELECT id FROM church_roles WHERE name = :name), :name, :level, :scope, CURRENT_TIMESTAMP)'
            : 'INSERT INTO church_roles (name, hierarchy_level, scope, created_at) VALUES (:name, :level, :scope, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE hierarchy_level = VALUES(hierarchy_level), scope = VALUES(scope)';
        $stmt = $pdo->prepare($sql);
        foreach ($roles as $role) {
            $stmt->execute(['name' => $role[0], 'level' => $role[1], 'scope' => $role[2]]);
        }
    }

    private function seedAdmin(PDO $pdo): void
    {
        $password = password_hash('Admin@12345', PASSWORD_BCRYPT);
        if ($this->driver === 'sqlite') {
            $pdo->exec("INSERT OR REPLACE INTO users (id, name, email, phone, password, role, is_adventist, country, region_id, district_id, church_id, created_at, updated_at) VALUES (1, 'Administrador Geral', 'admin@demo.com', '+244900000000', '{$password}', 'admin', 1, 'Angola', 1, 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        } else {
            $pdo->exec("INSERT INTO users (id, name, email, phone, password, role, is_adventist, country, region_id, district_id, church_id, created_at, updated_at) VALUES (1, 'Administrador Geral', 'admin@demo.com', '+244900000000', '{$password}', 'admin', 1, 'Angola', 1, 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE password = VALUES(password)");
        }
    }
}
