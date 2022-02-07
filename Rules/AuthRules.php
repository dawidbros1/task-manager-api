<?php

declare(strict_types=1);

namespace Rules;

use Model\Rules;

class AuthRules extends Rules
{
    public function __construct()
    {
        $this->rules();
        $this->messages();
    }

    public function rules()
    {
        $this->createRules('email', ['sanitize' => true, "validate" => true]);
        $this->createRules('username', ['min' => 3, "max" => 16, 'specialCharacters' => true]);
        $this->createRules('password', ['min' => 6, 'max' => 36]);
    }

    public function messages()
    {
        $this->createMessages('email', [
            'sanitize' => "Adres email zawiera niedozwolone znaki",
            'validate' => "Adres email nie jest prawidłowy",
        ]);

        $this->createMessages('username', [
            'between' => "Nazwa użytkownika powinna zawierać od " . $this->value('username.min') . " do " . $this->value('username.max') . " znaków",
            'specialCharacters' => "Nazwa użytkownika nie może zawierać znaków specjalnych",
        ]);

        $this->createMessages('password', [
            'between' => "Hasło powinno zawierać od " . $this->value('password.min') . " do " . $this->value('password.max') . " znaków",
        ]);
    }
}
