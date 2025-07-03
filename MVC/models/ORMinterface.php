<?php
/**
 * Simple ORM interface to ensure consistency across models.
 */
interface ORMinterface {
    /**
     * Persist the current model to the database.
     * @return bool
     */
    public function Save(): bool;

    /**
     * Delete the current model from the database.
     * @return bool
     */
    public function Delete(): bool;

    /**
     * Get the primary key value of the model.
     * @return int|null
     */
    public function GetID(): ?int;
}
