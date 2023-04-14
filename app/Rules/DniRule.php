<?php

namespace app\Rules;

use Illuminate\Contracts\Validation\Rule;

class DniRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $dnisL = substr($value, 0, -1);
        $letra = substr($value, -1);
        $lista = "TRWAGMYFPDXBNJZSQVHLCKE";
        $arLista = str_split($lista);

        if (strlen($dnisL) == 8 && is_numeric($dnisL)) {
            $resultado = (int)$dnisL % 23;
            $letraAsign = $arLista[$resultado];
            if ($letra == $letraAsign) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El DNI es inválido.';
    }
}
