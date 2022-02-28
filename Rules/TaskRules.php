<?php

declare(strict_types=1);

namespace Rules;

use Model\General\Rules;

class TaskRules extends Rules
{
   public function __construct()
   {
      $this->rules();
      $this->messages();
   }

   public function rules()
   {
      $this->createRules('name', ['min' => 3, "max" => 64, 'specialCharacters' => true]);
      $this->createRules('description', ['max' => 2550]);
   }

   public function messages()
   {
      $this->createMessages('name', [
         'between' => "Nazwa zadania powinna zawierać od " . $this->value('name.min') . " do " . $this->value('name.max') . " znaków",
         'specialCharacters' => "Nazwa zadania nie może zawierać znaków specjalnych",
      ]);

      $this->createMessages('description', [
         'max' => "Opis nie może zawierać więcej niż " . $this->value('description.max') . " znaków",
      ]);
   }
}
