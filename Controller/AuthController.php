<?php

namespace Controller;

use Rules\AuthRules;
use Model\Auth;

class AuthController extends Controller
{
   public function __construct($action)
   {
      parent::__construct($action);
      $this->model = new Auth;
      $this->rules = new AuthRules();
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
      $names = ['email', 'password'];

      // if ($this->request->isGet() && $this->request->hasGetNames($names)) {
      //    $data = $this->request->getParams($names);
      //    $user = $this->model->login($data['email'], $data['password']);

      //    if ($user == null) {
      //       $this->response->error(404, "Nieprawidłeowe dane logowania");
      //    } else {
      //       $this->response->success($user);
      //    }

      // if ($id = $this->model->login($data['email'], $this->hash($data['password']))) {
      // Session::set('user:id', $id);
      // $lastPage = Session::getNextClear('lastPage');
      // $this->redirect($lastPage ? "?" . $lastPage : self::$route->get('home'));
      // } else {
      // if (in_array($data["email"], $this->repository->getEmails())) {
      //     Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
      // } else {
      //     Session::set("error:email:null", "Podany adres email nie istnieje");
      // }

      // unset($data['password']);
      // $this->redirect(self::$route->get('auth.login'), $data);
      // }
      // } else {
      // $this->response->error(404, "Brakujące parametry w formularzu");
      //   $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
      // }
   }
}
