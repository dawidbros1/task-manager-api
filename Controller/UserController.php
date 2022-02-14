<?php

namespace Controller;

use Rules\UserRules;

class UserController extends Controller
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->rules = new UserRules();
    }

    public function updateUsernameAction()
    {
        $data = $this->getData(['username']);

        [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

        if ($validateStatus) {
            $this->user->updateUsername((array) $data);
            $this->response->success();
        }

        $this->response->error(403, $validateMessages);
    }

    public function updatePasswordAction()
    {
        $data = $this->getData(['currentPassword', 'password', 'repeatPassword']);

        $password = $this->user->getProperty($data->id, 'password');

        [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

        if (!$correctPassword = ($password === $data->currentPassword)) {
            $validateMessages['currentPassword']['same'] = "Podane hasło jest nieprawidłowe";
        }

        if ($validateStatus && $correctPassword) {
            $this->user->updatePassword((array) $data);
            $this->response->success();
        }

        $this->response->error(403, $validateMessages);
    }
}
