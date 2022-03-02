<?php

namespace Model;

use Model\General\Database;

use PDO;

class Task extends Database
{
   public function create(array $input)
   {
      $data = [
         'project_id' => $input['project_id'],
         'name' => $input['name'],
         'description' => $input['description'],
         'status' => 0,
         'created' => date('Y-m-d H:i:s'),
      ];

      $sql = "INSERT INTO tasks (project_id, name, description, status, created) 
         VALUES (:project_id, :name, :description, :status, :created)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);

      return $this->get($this->pdo->lastInsertId());
   }

   public function get($id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id=:id");
      $stmt->execute(['id' => $id]);
      $task = $stmt->fetch(PDO::FETCH_ASSOC);
      return $task;
   }

   public function update(array $data)
   {
      $stmt = $this->pdo->prepare(
         "UPDATE tasks SET name=:name, 
         description=:description, status=:status 
         WHERE id=:id"
      );

      $stmt->execute([
         'id' => $data['id'],
         'name' => $data['name'],
         'description' => $data['description'],
         'status' => $data['status']
      ]);
   }

   public function delete(int $id)
   {
      $sql = "DELETE FROM tasks WHERE id=:id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['id' => $id]);
   }

   public function authorize($user_id, $project_id)
   {
      $stmt = $this->pdo->prepare("SELECT id FROM projects WHERE id=:id AND user_id=:user_id");
      $stmt->execute(['id' => $project_id, 'user_id' => $user_id]);
      $project = $stmt->fetch(PDO::FETCH_ASSOC);
      return $project;
   }
}
