<?php

namespace Controllers\Recipe;

require_once __DIR__ . '/../../services/mysql/recipe/queries.php';
require_once __DIR__ . '/../utils/params.php';

use Controllers\Utils\UriParams;
use Services\MySql\Queries\Recipe\QueryHolder as RecipeQueryHolder;

/**
 * Controller for recipe operations
 */
class Controller {
    use UriParams;

    static $state = [
        'pagination' => [
            'current_page' => 1,
            'total_page_count' => 1,
            'total_recipe_count' => 0
        ]
    ];

    /**
     * Get the latest recipes
     * @param int $limit The number of recipes to return
     * @return \Models\Recipe[] The latest recipes
     */
    public static function getLatestRecipes($limit) {
        return RecipeQueryHolder::getNewest($limit);
    }

    /**
     * Get paginated recipes
     * @param int $pagination_limit The number of recipes per page
     * @return \Models\Recipe[] The paginated recipes
     */
    public static function getPaginatedRecipes($pagination_limit) {
        $results = RecipeQueryHolder::getAllPaginated($pagination_limit, Controller::getRecipeFilteringParams());

        // Get the total number of recipes to verify that the index is valid
        $total_recipe_count = $results['count'];
        $total_expected_page_count = max(1, ceil($total_recipe_count / $pagination_limit));

        // Set the total number of pages and recipes in the pagination state
        self::$state['pagination']['total_page_count'] = $total_expected_page_count;
        self::$state['pagination']['total_recipe_count'] = $total_recipe_count;

        if (!isset($_GET['page']) || !is_numeric($_GET['page'])) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=1');
            die();
        }

        $page = (int)$_GET['page'];

        if ($page < 1 || $page > $total_expected_page_count) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=1');
            die();
        }

        // Set the current page in the pagination state
        self::$state['pagination']['current_page'] = $page;

        // Retrieve the corresponding paginated results and return them
        $current_page_count = 0;
        
        foreach ($results['generator'] as $recipes) {
            ++$current_page_count;
            
            if ($current_page_count !== $page)
                continue;
            
            return $recipes;
        }
    }

    /**
     * Get a single recipe by id
     * @param int $id The id of the recipe
     * @return \Models\Recipe The recipe found for that provided id
     */
    public static function getRecipeById() {
        $id = Controller::getNumericId();

        if ($id === null || $id < 1) {
            echo 'Error: must request an id for a recipe';
            return;
        }

        return RecipeQueryHolder::getById($id);
    }
}