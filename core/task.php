<?php

class Task
{
    private $conn;
    private $table = 'tasks';

    public $id;
    public $description;
    public $completed;
    public $user_id;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT t.description, t.created_at as initiated_at, t.completed, t.id, 
        t.user_id,  u.name
        FROM ' . $this->table . ' t INNER JOIN users u ON u.id = t.user_id ORDER BY initiated_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        $query = 'SELECT t.description, t.created_at, t.completed, t.id, 
        t.user_id,  u.name
        FROM ' . $this->table . ' t INNER JOIN users u ON u.id = t.user_id WHERE t.id = :id LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        $query = 'INSERT INTO ' . $this->table .
            ' SET description = :description, completed = :completed, user_id = :user_id';

        $stmt = $this->conn->prepare($query);

        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->completed = htmlspecialchars(strip_tags($this->completed));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':completed', $this->completed);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $query = 'UPDATE ' . $this->table .
            ' SET description = :description, completed = :completed WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->completed = htmlspecialchars(strip_tags($this->completed));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':completed', $this->completed);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }
}
