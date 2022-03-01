<?php

namespace Model;

use Model\General\Database;

use PDO;

class Task extends Database
{
   public function authorize($user_id, $project_id)
   {
      $stmt = $this->pdo->prepare("SELECT id FROM projects WHERE id=:id AND user_id=:user_id");
      $stmt->execute(['id' => $project_id, 'user_id' => $user_id]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$data) $this->response->error(400, "Brak uprawnień do tego projektu");
   }

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

      return $this->get($this->pdo->lastInsertId(), $input['user_id']);
   }

   public function get($id, $user_id)
   {
      $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id=:id");
      $stmt->execute(['id' => $id]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$data) $this->response->error(400, "Zasób o podanym ID nie istnieje");
      $this->authorize($user_id, $data['project_id']);

      return $data;
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
}
