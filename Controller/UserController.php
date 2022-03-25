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

        $this->response->validateError($validateMessages);
    }

    public function updatePasswordAction()
    {
        $data = $this->getData(['currentPassword', 'password', 'repeatPassword']);
        $data->currentPassword = $this->hash($data->currentPassword);

        $password = $this->user->getProperty($data->user_id, 'password');
        [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

        if (!$correctPassword = ($password === $data->currentPassword)) {
            $validateMessages['currentPassword']['same'] = "Podane hasło jest nieprawidłowe";
        }

        if ($validateStatus && $correctPassword) {
            $data->password = $this->hash($data->password);
            $this->user->updatePassword((array) $data);
            $this->response->success();
        }

        $this->response->validateError($validateMessages);
    }
}
