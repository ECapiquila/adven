<?php

namespace App\Services;

class WalletService
{
    public function balance(int $userId): array
    {
        return ['saldo' => 0.0, 'moeda' => 'AOA'];
    }
}
