<?php

namespace Controller;

use Rules\AuthRules;
use Model\Auth;
use stdClass;

class UserController extends Controller
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->model = new Auth;
        $this->rules = new AuthRules();
    }

    public function updateUsername()
    {
        $data = json_decode(file_get_contents("php://input"));
        $names = ['id', 'username'];

        if (!$this->request->hasProperties($data, $names)) {
            $this->response->error(400, "Brakujące parametry w formularzu");
        }
    }
}
