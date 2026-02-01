<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Lightweight validator to complement Controller::validate when needed.
 */
final class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $fieldRules = explode('|', $ruleString);

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field][] = "El campo {$field} es requerido";
                    continue;
                }

                if ($rule === 'email' && $value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "El campo {$field} debe ser un email valido";
                    continue;
                }

                if (preg_match('/^min:(\d+)$/', $rule, $m)) {
                    $min = (int) $m[1];
                    if ($value !== null && $value !== '' && mb_strlen((string) $value) < $min) {
                        $errors[$field][] = "El campo {$field} debe tener al menos {$min} caracteres";
                        continue;
                    }
                }

                if (preg_match('/^max:(\d+)$/', $rule, $m)) {
                    $max = (int) $m[1];
                    if ($value !== null && $value !== '' && mb_strlen((string) $value) > $max) {
                        $errors[$field][] = "El campo {$field} no debe exceder {$max} caracteres";
                        continue;
                    }
                }
            }
        }

        return $errors;
    }
}