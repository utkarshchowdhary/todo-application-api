<?php

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $age;
    public $residence;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT t.description, t.created_at as initiated_at, t.completed, t.id as task_id, 
        u.id,  u.name, u.age, u.residence, u.created_at
        FROM ' . $this->table . ' u LEFT JOIN tasks t ON u.id = t.user_id ORDER BY u.created_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        $query = 'SELECT t.description, t.created_at as initiated_at, t.completed, t.id as task_id, 
        u.id,  u.name, u.age, u.residence, u.created_at
        FROM ' . $this->table . ' u LEFT JOIN tasks t ON u.id = t.user_id WHERE u.id = :id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        $query = 'INSERT INTO ' . $this->table .
            ' SET name = :name, age = :age, residence = :residence';

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->residence = htmlspecialchars(strip_tags($this->residence));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':residence', $this->residence);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $query = 'UPDATE ' . $this->table .
            ' SET name = :name, age = :age, residence = :residence WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->residence = htmlspecialchars(strip_tags($this->residence));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':residence', $this->residence);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = 'DELETE u, t FROM ' . $this->table .
            ' u LEFT JOIN tasks t ON u.id = t.user_id WHERE u.id = ?';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }
}
