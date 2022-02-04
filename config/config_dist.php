<?php

declare(strict_types=1);

use Model\Config;

return new Config(
   [
      'db' => [
         'host' => '',
         'database' => '',
         'user' => '',
         'password' => '',
      ],
      'hash' => [
         'method' => '', // sha25 || md5 ...
      ],
   ]
);
