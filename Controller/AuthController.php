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
      $data = $this->getData(['username', 'email', 'password', 'repeatPassword'], false);

      [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

      if (!$isEmailUnique = $this->model->isEmailUnique($data->email)) {
         $validateMessages['email']['taken'] = "Podany adres email jest już zajęty";
      }

      if ($validateStatus && $isEmailUnique) {
         [$sideKey, $secretKey] = $this->generateKeys($data->email);

         $data->sideKey = $sideKey;
         $data->secretKey = $secretKey;
         $data->password = $this->hash($data->password); // Hash passwrod

         $this->model->register((array) $data);
         $this->response->success();
      }

      $this->response->validateError($validateMessages);
   }

   public function loginAction(): void
   {
      $data = $this->getData(['email', 'password'], false);

      if ($user = $this->model->login($data->email, $this->hash($data->password))) {
         $object = new stdClass();
         $object->user = $user;
         $this->response->success($object);
      }

      if ($this->model->isEmailUnique($data->email)) {
         $validateMessages['email']['notExist'] = "Podany adres email nie istnieje";
      } else {
         $validateMessages['password']['notCorrect'] = "Hasło jest błędne";
      }

      $this->response->validateError($validateMessages);
   }
}
