<?php

namespace Model;

use Model\General\Database;

use PDO;

class Auth extends Database
{
   public function register(array $data): void
   {
      $data = [
         'username' => $data['username'],
         'email' => $data['email'],
         'password' => $data['password'],
         'created' => date('Y-m-d H:i:s'),
      ];

      $sql = "INSERT INTO users (username, email, password, created) 
         VALUES (:username, :email, :password, :created)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
   }

   public function login(string $email, string $password)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
      $stmt->execute([
         'email' => $email,
         'password' => $password,
      ]);

      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($data) $data['password'] = "";

      return $data;
   }

   public function isEmailUnique(string $email)
   {
      $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email=:email");
      $stmt->execute(['email' => $email]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($data == false) return true;
      else return false;
   }
}
