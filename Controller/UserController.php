<?php

namespace Controller;

use Rules\UserRules;
use Model\User;
use stdClass;

class UserController extends Controller
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->model = new User();
        $this->rules = new UserRules();
    }

    public function updateUsernameAction()
    {
        $data = $this->getData(['id', 'username']);

        [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

        if ($validateStatus) {
            $this->model->updateUsername((array) $data);
            $this->response->success();
        }

        $this->response->error(403, $validateMessages);
    }

    public function updatePasswordAction()
    {
        $data = $this->getData(['id', 'currentPassword', 'password', 'repeatPassword']);
        $user = $this->model->getUser($data->id);

        [$validateStatus, $validateMessages] = $this->validate((array) $data, $this->rules);

        // SPRAWDZ AUTORYZACJE API KEY

        if (!$correctPassword = ($user['password'] === $data->currentPassword)) {
            $validateMessages['currentPassword']['same'] = "Podane hasło jest nieprawidłowe";
        }

        if ($validateStatus && $correctPassword) {
            $this->model->updatePassword((array) $data);
            $this->response->success();
        }

        $this->response->error(403, $validateMessages);
    }
}
