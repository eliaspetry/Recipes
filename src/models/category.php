<?php

namespace Models;

require_once __DIR__ . '/model.php';

/**
 * Category DB columns/fields trait
 */
trait CategoryFields {
    public string $name;
    public ?int $id;
}

/**
 * Category model
 */
class Category extends Model {
    use CategoryFields;

    /**
     * Category constructor
     * @param string $name The name of the category
     * @param ?int $id The id of the category
     * @return void
     */
    public function __construct($name, $id = null) {
        $this->id = $id;
        $this->name = $name;
    }
}