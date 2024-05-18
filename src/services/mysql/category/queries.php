<?php

namespace Services\MySql\Queries\Category;

require_once __DIR__ . '/../connector.php';
require_once __DIR__ . '/../../../models/category.php';

use DB;
use Models\Category;

/**
 * Category queries
 */
class QueryHolder
{
    /**
     * Creates a new category
     * @param \Models\Category $category The category to insert
     */
    public static function insertIgnore($category)
    {
        DB::insertIgnore('categories', [
            'name' => $category->name
        ]);
    }

    /**
     * Get all categories for a recipe
     * @param int $recipe_id The id of the recipe
     * @return \Models\Category[] An array of categories for the given recipe
     */
    public static function getCategoriesForRecipe($recipe_id)
    {
        $results = DB::query('SELECT categories.* FROM categories, recipes_categories WHERE recipes_categories.category_id = categories.id AND recipes_categories.recipe_id = %i', $recipe_id);
        $categories = [];

        foreach ($results as $row) {
            array_push($categories, new Category($row['name'], $row['id']));
        }

        return $categories;
    }

    /**
     * Gets all categories
     * @return \Models\Category[] An array of all categories
     */
    public static function getCategories()
    {
        $results = DB::query('SELECT * FROM categories');
        $categories = [];

        foreach ($results as $row) {
            array_push($categories, new Category($row['name'], $row['id']));
        }

        return $categories;
    }

    /**
     * Checks if a category id is valid
     * @param int $id The id to check
     * @return bool Whether the id is valid or not
     */
    public static function isCategoryIdValid($id)
    {
        $result = DB::queryFirstRow('SELECT * FROM categories WHERE id = %i', $id);

        return $result != null;
    }
}
