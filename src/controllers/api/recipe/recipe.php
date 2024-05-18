<?php

namespace Controllers\Api\Recipe;

require_once __DIR__ . '/../../../services/mysql/recipe/queries.php';
require_once __DIR__ . '/../../utils/params.php';

use Services\MySql\Queries\Recipe\QueryHolder as RecipeQueryHolder;
use Controllers\Utils\UriParams;

/**
 * Controller for API recipe operations
 */
class Controller {
    use UriParams;

    /**
     * Get all recipes for a specific page and output them as JSON
     * @param int $pagination_limit The number of recipes to return per page
     * @return void
     */
    public static function getPaginatedRecipes($pagination_limit) {
        $page = Controller::getNumericId();

        if ($page === null) {
            echo 'Error: must request a page index';
            return;
        }

        $results = RecipeQueryHolder::getAllPaginated($pagination_limit, Controller::getRecipeFilteringParams());

        // Get the total number of recipes to verify that the index is valid
        $total_recipe_count = $results['count'];
        $total_expected_page_count = max(1, ceil($total_recipe_count / $pagination_limit));

        if ($page < 1 || $page > $total_expected_page_count) {
            echo 'Error: must request a valid index for a page';
            return;
        }

        // Retrieve the corresponding paginated results and return them as JSON
        $current_page_count = 0;
        
        foreach ($results['generator'] as $recipes) {
            ++$current_page_count;
            
            if ($current_page_count !== $page)
                continue;

            $json = [
                'totalRecipeCount' => $total_recipe_count,
                'totalPageCount' => $total_expected_page_count,
                'currentPageIndex' => $page,
                'recipes' => array_map(function($recipe) {
                    return $recipe->serialize();
                }, $recipes)
            ];

            echo json_encode($json);
            return;
        }
    }

    /**
     * Get a specific recipe and output it as JSON
     * @return void
     */
    public static function getRecipeById() {
        $id = Controller::getNumericId();

        if ($id === null || $id < 1) {
            echo 'Error: must request an id for a recipe';
            return;
        }

        // Try to get the corresponding recipe and return it as JSON; else, raise an error if not found
        $recipe = RecipeQueryHolder::getById($id);

        if (!$recipe) {
            echo 'Error: recipe with id ' . $id . ' not found';
            return;
        }

        echo json_encode($recipe->serialize());
    }
}