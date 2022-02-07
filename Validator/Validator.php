<?php

declare(strict_types=1);

namespace Validator;

use App\Helper\Session;

// use App\Excep

class Validator
{
    // Metody walidacyjne wielokrotnego użytku
    protected function strlenBetween(string $variable, int $min, int $max)
    {
        if (strlen($variable) > $min && strlen($variable) < $max) {
            return true;
        }

        return false;
    }

    protected function strlenMax(string $input, int $max)
    {
        if (strlen($input) > $max) {
            return false;
        }

        return true;
    }

    protected function strlenMin(string $input, int $min)
    {
        if (strlen($input) < $min) {
            return false;
        }

        return true;
    }

    // Ogólna klasa VALIDATORA

    public function validate(array $data, $rules)
    {
        $types = array_keys($data);
        $messages = [];

        if (array_key_exists('password', $data) && array_key_exists('repeat_password', $data)) {
            if ($data['password'] != $data['repeat_password']) {
                // Session::set("error:repeat_password:same", "Hasła nie są jednakowe");
            }
        }

        foreach ($types as $type) {
            if (!$rules->hasType($type)) {
                continue;
            }

            $rules->selectType($type);
            $between = (bool) $rules->hasKeys(['min', 'max']);
            $betweenValidate = false;
            $input = (string) $data[$type];

            foreach (array_keys($rules->get()) as $rule) {
                $value = $rules->value($rule);
                $message = $rules->message($rule);
                $ok = true;

                // ================================================
                if (($rule == "min" || $rule == "max") && $between == true && $betweenValidate == false) {
                    $min = $rules->value('min');
                    $max = $rules->value('max');
                    $rule = "between";

                    if ($this->strlenBetween($input, $min - 1, $max + 1) == false) {
                        $ok = false;
                    }

                    $betweenValidate = true;
                }
                // ================================================
                else if ($rule == "max" && $betweenValidate == false) {
                    if ($this->strlenMax($input, $value) == false) {
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "min" && $betweenValidate == false) {
                    if ($this->strlenMin($input, $value) == false) {
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "validate" && $value == true) {
                    if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "sanitize" && $value == true) {
                    if ($input != filter_var($input, FILTER_SANITIZE_EMAIL)) {
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "require" && $value == true) {
                    if (empty($input)) {
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "specialCharacters" && $value == true) {
                    if (preg_match('/[\'^£$%&*()}{@#~"?><>,|=_+¬-]/', $input)) {
                        $ok = false;
                    }
                }
                // ================================================

                if ($ok == false) {
                    $validate = false;
                    $messages[$type][$rule] = $message;
                }
            }
        }

        return [$validate ?? true, $messages];
    }
}
