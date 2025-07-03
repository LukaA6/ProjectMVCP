<?php
/**
 * Base class implementing common ORM behaviour.
 */
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/ORMinterface.php';

abstract class AbstractModel implements ORMinterface {
    protected \PDO $db;
    protected ?int $id = null;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    
    abstract protected static function tableName(): string;
    abstract protected static function primaryKey(): string;
    abstract protected function fill(array $attributes): void;

    public function GetID(): ?int {
        return $this->id;
    }

    

    /**
     * @param \PDO $db
     * @param int $id
     * @return static|null
     */
    public static function FindByID(\PDO $db, int $id): ?self {
        $stmt = $db->prepare("SELECT * FROM " . static::tableName() . " WHERE " . static::primaryKey() . " = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return null;
        $instance = new static($db);
        $instance->fill($row);
        return $instance;
    }

    /**
     * Return all rows for a model.
     * @param \PDO $db
     * @return static[]
     */
    public static function FindAll(\PDO $db): array {
        $stmt = $db->query("SELECT * FROM " . static::tableName());
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $instance = new static($db);
            $instance->fill($row);
            $results[] = $instance;
        }
        return $results;
    }
}
