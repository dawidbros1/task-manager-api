<?php

namespace Controller;

use Rules\UserRules;
use Model\Auth;
use stdClass;

class AuthController extends Controller
{
   public function __construct($action)
   {
      parent::__construct($action);
      $this->model = new Auth;
      $this->rules = new UserRules();
   }

   public function registerAction(): void
   {
      $data = $this->getData(['username', 'email', 'password', 'repeatPassword']);

      [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

      if (!$isEmailUnique = $this->model->isEmailUnique($data->email)) {
         $validateMessages['email']['taken'] = "Podany adres email jest już zajęty";
      }

      if ($validateStatus && $isEmailUnique) {
         [$side_key, $secret_key] = $this->generateKeys($data->email);

         $data->side_key = $side_key;
         $data->secret_key = $secret_key;

         $this->model->register((array) $data);
         $this->response->success();
      }

      $this->response->error(403, $validateMessages);
   }

   public function loginAction(): void
   {
      $data = $this->getData(['email', 'password']);

      if ($user = $this->model->login($data->email, $data->password)) {
         $object = new stdClass();
         $object->user = $user;
         $this->response->success($object);
      }

      if ($this->model->isEmailUnique($data->email)) {
         $validateMessages['email']['notExist'] = "Podany adres email nie istnieje";
      } else {
         $validateMessages['password']['notCorrect'] = "Hasło jest błędne";
      }

      $this->response->error(403, $validateMessages);
   }
}
