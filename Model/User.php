<?php

namespace Model;

use Model\General\Database;

use PDO;

class User extends Database
{
    public function updateUsername(array $data)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET username=:username WHERE id=:id");
        $stmt->execute([
            'id' => $data['id'],
            'username' => $data['username']
        ]);
    }

    public function updatePassword(array $data)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password=:password WHERE id=:id");
        $stmt->execute([
            'id' => $data['id'],
            'password' => $data['password']
        ]);
    }

    public function getUser(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}
