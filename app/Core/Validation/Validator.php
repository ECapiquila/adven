<?php

namespace App\Core\Validation;

class Validator
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $rulesList = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rulesList as $rule) {
                [$ruleName, $parameter] = array_pad(explode(':', $rule, 2), 2, null);

                if ($ruleName === 'required' && ($value === null || $value === '')) {
                    $errors[$field][] = 'campo obrigatório';
                }

                if ($ruleName === 'email' && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'formato de e-mail inválido';
                }

                if ($ruleName === 'min' && $value && strlen((string) $value) < (int) $parameter) {
                    $errors[$field][] = 'valor curto demais';
                }
            }
        }

        return $errors;
    }
}
