<?php

namespace Model;

use Model\General\Database;

use PDO;

class Project extends Database
{
   public function getAll(int $user_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE user_id=:user_id");
      $stmt->execute(['user_id' => $user_id]);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $data;
   }

   public function create(array $data)
   {
      $data = [
         'user_id' => $data['user_id'],
         'name' => $data['name'],
         'description' => $data['description'],
         'created' => date('Y-m-d H:i:s'),
      ];

      $sql = "INSERT INTO projects (user_id, name, description, created) 
         VALUES (:user_id, :name, :description, :created)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);

      return $this->get($this->pdo->lastInsertId());
   }

   private function get($id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE id=:id");
      $stmt->execute(['id' => $id]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      return $data;
   }
}
