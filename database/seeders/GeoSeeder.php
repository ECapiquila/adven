<?php

namespace Database\Seeders;

use App\Core\Database\Connection;

class GeoSeeder
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function run(): void
    {
        $pdo = $this->connection->getPdo();
        $pdo->exec('CREATE TABLE IF NOT EXISTS geo_municipios (id INT AUTO_INCREMENT PRIMARY KEY, provincia VARCHAR(120), municipio VARCHAR(120))');
        $csv = fopen(__DIR__ . '/../data/angola_municipios.csv', 'r');
        fgetcsv($csv);
        $stmt = $pdo->prepare('INSERT INTO geo_municipios (provincia, municipio) VALUES (:provincia, :municipio)');
        while (($row = fgetcsv($csv)) !== false) {
            $stmt->execute(['provincia' => $row[0], 'municipio' => $row[1]]);
        }
        fclose($csv);
    }
}
