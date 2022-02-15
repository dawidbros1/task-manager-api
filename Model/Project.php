<?php

namespace Model;

use Model\General\Database;

use PDO;

class Project extends Database
{
   public function get(int $user_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE user_id=:user_id");
      $stmt->execute(['user_id' => $user_id]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      return $data;
   }

   public function create(array $data): void
   {
      $data = [
         'user_id' => $data['id'],
         'name' => $data['name'],
         'description' => $data['description'],
         'created' => date('Y-m-d H:i:s'),
      ];

      $sql = "INSERT INTO projects (user_id, name, description, created) 
         VALUES (:user_id, :name, :description, :created)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
   }
}
