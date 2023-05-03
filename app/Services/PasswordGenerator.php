<?php

namespace App\Services;

class PasswordGenerator
{
    public function generatePass()
    {
        $password = '';
        $length = 8;

        // Generar la contraseña aleatoria
        $upper = false;
        $number = false;
        while (strlen($password) < $length || !$upper || !$number) {
            $char = chr(random_int(33, 126));
            if (ctype_upper($char)) {
                $upper = true;
            } elseif (ctype_digit($char)) {
                $number = true;
            }
            $password .= $char;
        }
        return $password;
    }
}
