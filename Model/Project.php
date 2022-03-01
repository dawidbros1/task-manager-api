<?php

namespace Model;

use Model\General\Database;

use PDO;

class Project extends Database
{
   public function get($id, $user_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE id=:id AND user_id=:user_id");
      $stmt->execute(['id' => $id, 'user_id' => $user_id]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$data) $this->response->error(400, "Zasób o podanym ID nie istnieje");
      else return $data;
   }

   public function getTasks(int $project_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE project_id=:project_id");
      $stmt->execute(['project_id' => $project_id]);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (!$data) return [];
      else return $data;
   }

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

      return $this->get($this->pdo->lastInsertId(), $data['user_id']);
   }

   public function update(array $data)
   {
      $stmt = $this->pdo->prepare("UPDATE projects SET name=:name, description=:description WHERE id=:id");
      $stmt->execute([
         'id' => $data['id'],
         'name' => $data['name'],
         'description' => $data['description']
      ]);
   }

   public function delete(array $data)
   {
      $sql = "DELETE FROM projects WHERE id=:id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['id' => $data['id']]);
   }
}
