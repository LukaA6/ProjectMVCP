<?php

require_once __DIR__ . '/AbstractModel.php';

class User extends AbstractModel {
    public string $username;
    public string $email;
    public string $password;

    protected static function tableName(): string { return 'users'; }
   
    protected static function primaryKey(): string { return 'id'; }

   
    protected function fill(array $attributes): void {
        $this->id       = (int)$attributes['id'];
        $this->username = $attributes['username'];
        $this->email    = $attributes['email'];
        $this->password = $attributes['password'];
    }

   
    public function Save(): bool {
        if ($this->id) {
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            return $stmt->execute([$this->username, $this->email, $this->password, $this->id]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $success = $stmt->execute([$this->username, $this->email, $this->password]);
            if ($success) {
                $this->id = (int)$this->db->lastInsertId();
            }
            return $success;
        }
    }


    public function Delete(): bool {
        if (!$this->id) return false;
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}
