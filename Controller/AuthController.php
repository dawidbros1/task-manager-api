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
      $data = json_decode(file_get_contents("php://input"));
      $names = ['username', 'email', 'password', 'repeatPassword'];

      if (!$this->request->hasProperties($data, $names)) {
         $this->response->error(400, "Brakujące parametry w formularzu");
      }

      [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

      if (!$isEmailUnique = $this->model->isEmailUnique($data->email)) {
         $validateMessages['email']['taken'] = "Podany adres email jest już zajęty";
      }

      if ($validateStatus && $isEmailUnique) {
         $this->model->register((array) $data);
         $this->response->success();
      }

      $this->response->error(403, $validateMessages);
   }

   public function loginAction(): void
   {
      $data = json_decode(file_get_contents("php://input"));
      $names = ['email', 'password'];

      if (!$this->request->hasProperties($data, $names)) {
         $this->response->error(400, "Brakujące parametry w formularzu");
      }

      if ($user = $this->model->login($data->email, $data->password)) {
         $user = (object) $user;
         $user->password = "";

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
