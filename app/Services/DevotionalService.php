<?php

namespace App\Services;

class DevotionalService
{
    public function daily(): array
    {
        return [
            'manha' => 'Mensagem da manhã',
            'tarde' => 'Mensagem da tarde',
            'noite' => 'Mensagem da noite',
        ];
    }
}
