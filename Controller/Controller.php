<?php

namespace Controller;

use DateTime;
use Model\General\Database;
use Model\General\Response;
use Helper\Request;
use Model\User;
use stdClass;
use Validator\Validator;

abstract class Controller extends Validator
{
    protected static $config;

    protected Request $request;
    protected Response $response;

    protected $hashMethod;

    public static function initConfiguration($config): void
    {
        self::$config = $config;
    }

    public function __construct($action)
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->action = $action;

        Database::initConfiguration(self::$config->get('db'));
        $this->user = new User();
        $this->hashMethod = self::$config->get('hash.method');
    }

    public function run()
    {
        if (!method_exists($this, $this->action . "Action")) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->response->error(400, "Action [" . $this->action . "] doesn't exists in " . $className);
        }

        $action = $this->action . "Action";
        $this->$action();
    }

    protected function hash($param, $method = null)
    {
        return hash($method ?? $this->hashMethod, $param);
    }

    protected function getData($names, $authorize = true)
    {
        if ($authorize) array_push($names, 'user_id', 'sideKey');

        $input = json_decode(file_get_contents("php://input"));
        [$ok, $missingFields] = $this->request->hasProperties($input, $names);

        if (!$ok) $this->response->error(400, "Brakujące parametry w formularzu to: " . $missingFields);
        if ($authorize) $this->authorize($input->user_id, $input->sideKey);

        return $input;
    }

    protected function generateKeys(string $email)
    {
        $now = (DateTime::createFromFormat('U.u', microtime(true)))->format("U-u");
        $input = $now . rand(1, 1000000);

        $secretKey = $this->hash((string) $input);
        $sideKey = $this->createSideKey($secretKey, $email);
        return [$sideKey, $secretKey];
    }

    protected function createSideKey($secretKey, $email)
    {
        return $this->hash((string) ($secretKey . $email));
    }

    protected function authorize($user_id, $sideKey)
    {
        $data = $this->user->getProperties($user_id, ['email', 'secret_key']);

        if (!$data) $this->response->error(403, "Podany użytkownik nie istnieje!");

        if ($sideKey != $this->createSideKey($data['secret_key'], $data['email'])) {
            $this->response->error(401, "Action is Unauthorized!");
        }
    }

    protected function createObject($data, $name)
    {
        $object = new stdClass();
        $object->$name = $data;
        return $object;
    }
}
