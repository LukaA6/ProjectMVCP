<?php

require_once __DIR__ . '/AbstractModel.php';

class Product extends AbstractModel {
    public string $name;
    public float $price;
    public ?string $image = null;

    protected static function tableName(): string { return 'products'; }
    protected static function primaryKey(): string { return 'id'; }

    protected function fill(array $attributes): void {
        $this->id    = (int)$attributes['id'];
        $this->name  = $attributes['name'];
        $this->price = (float)$attributes['price'];
        $this->image = $attributes['image'] ?? null;
    }

    public function Save(): bool {
        if ($this->id) {
            $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
            return $stmt->execute([$this->name, $this->price, $this->image, $this->id]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
            $success = $stmt->execute([$this->name, $this->price, $this->image]);
            if ($success) {
                $this->id = (int)$this->db->lastInsertId();
            }
            return $success;
        }
    }

    public function Delete(): bool {
        if (!$this->id) return false;
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}
