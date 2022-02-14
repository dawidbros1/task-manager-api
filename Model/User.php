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

    public function getProperty(int $id, string $property): string
    {
        $stmt = $this->pdo->prepare("SELECT $property FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) $data = $data[$property];
        return $data;
    }

    public function getProperties(int $id, array $properties)
    {
        $output = "";

        foreach ($properties as $property) $output = $output . $property . ",";

        $output = substr_replace($output, "", -1);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }
}
