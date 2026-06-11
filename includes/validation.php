<?php

function is_valid_client_address(string $address): bool
{
    $pattern = '/^[\p{L}\p{M}0-9\s\.\-]+?\s+\d+[a-zA-Z]?(?:\/\d+)?\s*,\s*(?:\d{4}\s+)?[\p{L}\p{M}\s\.\-]+$/u';

    return preg_match($pattern, $address) === 1;
}

function validate_password_strength(string $password): array
{
    $errors = [];

    if (strlen($password) < 15) {
        $errors[] = 'Geslo mora vsebovati vsaj 15 znakov.';
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Geslo mora vsebovati vsaj eno veliko črko.';
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Geslo mora vsebovati vsaj eno malo črko.';
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Geslo mora vsebovati vsaj eno številko.';
    }

    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Geslo mora vsebovati vsaj en poseben znak.';
    }

    return $errors;
}