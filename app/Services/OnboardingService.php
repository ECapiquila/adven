<?php

namespace App\Services;

class OnboardingService
{
    public function steps(): array
    {
        return [
            'perfil',
            'preferencias',
            'ministerios',
        ];
    }
}
