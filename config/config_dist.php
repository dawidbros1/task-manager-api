<?php

declare(strict_types=1);

use Model\General\Config;

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
