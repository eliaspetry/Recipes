<?php

namespace Controllers\Category;

require_once __DIR__ . '/../../services/mysql/category/queries.php';

use Services\MySql\Queries\Category\QueryHolder as CategoryQueryHolder;

/**
 * Controller for category operations
 */
class Controller
{
    /**
     * Get all categories
     * @return \Models\Category[] Array of categories
     */
    public static function getCategories()
    {
        return CategoryQueryHolder::getCategories();
    }

    /**
     * Check if category id is valid
     * @param int $id The category id to check
     * @return bool True if the provided category id is valid, else false
     */
    public static function isCategoryIdValid($id)
    {
        return CategoryQueryHolder::isCategoryIdValid($id);
    }
}
