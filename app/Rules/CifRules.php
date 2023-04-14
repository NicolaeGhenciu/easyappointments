<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CifRules implements Rule
{
    /**
     * Determina si el CIF es válido.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $cifRegEx1 = '/^[ABEH][0-9]{8}$/i';
        $cifRegEx2 = '/^[KPQS][0-9]{7}[A-J]$/i';
        $cifRegEx3 = '/^[CDFGJLMNRUVW][0-9]{7}[0-9A-J]$/i';

        if (preg_match($cifRegEx1, $value) || preg_match($cifRegEx2, $value) || preg_match($cifRegEx3, $value)) {
            $control = $value[strlen($value) - 1];
            $suma_A = 0;
            $suma_B = 0;

            for ($i = 1; $i < 8; $i++) {
                if ($i % 2 == 0) $suma_A += intval($value[$i]);
                else {
                    $t = (intval($value[$i]) * 2);
                    $p = 0;

                    for ($j = 0; $j < strlen($t); $j++) {
                        $p += substr($t, $j, 1);
                    }
                    $suma_B += $p;
                }
            }

            $suma_C = (intval($suma_A + $suma_B)) . "";
            $suma_D = (10 - intval($suma_C[strlen($suma_C) - 1])) % 10;

            $letras = "JABCDEFGHI";

            if ($control >= "0" && $control <= "9") return ($control == $suma_D);
            else return (strtoupper($control) == $letras[$suma_D]);
        } else return false;
    }

    /**
     * Obtén el mensaje de error.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not a valid CIF.';
    }
}
