<?php

namespace Model;

use Model\General\Database;

use PDO;

class Project extends Database
{
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

   public function get($id, $user_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE id=:id AND user_id=:user_id");
      $stmt->execute(['id' => $id, 'user_id' => $user_id]);
      $project = $stmt->fetch(PDO::FETCH_ASSOC);
      return $project;
   }

   public function getAll(int $user_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE user_id=:user_id");
      $stmt->execute(['user_id' => $user_id]);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $data;
   }

   public function getTasks(int $project_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE project_id=:project_id");
      $stmt->execute(['project_id' => $project_id]);
      $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $tasks ?? [];
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

   public function delete(int $project_id)
   {
      // DELETE PROJECT
      $sql = "DELETE FROM projects WHERE id=:id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['id' => $project_id]);

      // DELETE TASKS FORM PROJECT
      $sql = "DELETE FROM tasks WHERE project_id=:project_id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['project_id' => $project_id]);
   }
}
