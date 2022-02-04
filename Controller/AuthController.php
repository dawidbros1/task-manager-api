<?php

namespace Controller;

use Model\Auth;

class AuthController extends Controller
{
   public function __construct($action)
   {
      parent::__construct($action);
      $this->model = new Auth;
   }

   public function registerAction(): void
   {
      $names = ['username', 'email', 'password'];

      if ($this->request->isGet() && $this->request->hasGetNames($names)) {
         $data = $this->request->getParams($names);

         // $emails = $this->repository->getEmails();

         //   if ($this->validate($data, $this->rules) && !Auth::isBusyEmail($data['email'], $emails)) {

         if ($this->model->isUniqueEmail($data['email'])) {
            $this->model->register($data);
            $this->response->success();
         } else {
            $this->response->error(404, "The email address is already taken");
         }

         // $user = new User($data);
         // $user->escape();

         // $this->repository->register($user);
         // Session::set('success', 'Konto zostało utworzone');
         // $this->redirect(self::$route->get('auth.login'), ['email' => $user->email]);
         //   } else {
         // unset($data['password'], $data['repeat_password']);
         // $this->redirect(self::$route->get('auth.register'), $data);
         //   }
         // $this->response->set(200, "Wszystko jest ok");
      } else {
         $this->response->error(404, "Brakujące parametry w formularzu");
         // $this->view->render('auth/register', $this->request->getParams(['username', 'email']));
      }
   }

   public function loginAction(): void
   {
      $names = ['email', 'password'];

      if ($this->request->isGet() && $this->request->hasGetNames($names)) {
         $data = $this->request->getParams($names);
         $user = $this->model->login($data['email'], $data['password']);

         if ($user == null) {
            $this->response->error(404, "Nieprawidłeowe dane logowania");
         } else {
            $this->response->success($user);
         }

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
      } else {
         $this->response->error(404, "Brakujące parametry w formularzu");
         //   $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
      }
   }
}
