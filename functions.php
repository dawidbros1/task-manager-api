<?php

use Model\Response;

// uri[id] dependest from location on server
// uri[2] is ControllerName if location in root like a localhost/index.php
// uri[3] is ActionName if location in root like a localhost/index.php

function getController()
{
  $response = new Response;
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri = explode('/', $uri);
  $controllerName = $uri[4];

  $namespace = "Controller\\" . ucfirst($controllerName) . "Controller";

  if (!file_exists(__DIR__ . "\\" . $namespace . ".php")) {
    $response->error(400, "Controller [" . $controllerName . "] doesnt exists");
    $response->send();
  }

  $controller = new $namespace($uri[5]);

  return $controller;
}
