<?php

namespace Services\MySql\Queries\Recipe;

require_once __DIR__ . '/../connector.php';
require_once __DIR__ . '/../../../models/recipe.php';
require_once __DIR__ . '/../common/pagination.php';
require_once __DIR__ . '/../category/queries.php';

use DB;
use Models\Recipe;
use Services\MySql\Queries\Common\Pagination\QueryHolder as PaginationQueryHolder;
use Services\MySql\Queries\Category\QueryHolder as CategoryQueryHolder;

/**
 * Recipe queries
 */
class QueryHolder
{
    /**
     * Gets the most recently added recipes
     * @param int $limit The number of recipes to return
     * @return Models\Recipe[] The retrieved recipes
     */
    public static function getNewest($limit)
    {
        $results = DB::query('SELECT * FROM recipes ORDER BY datetime DESC LIMIT %d', $limit);
        $recipes = [];

        foreach ($results as $row) {
            $recipe = new Recipe(
                $row['name'],
                $row['difficulty'],
                $row['prep_time_minutes'],
                $row['image_url'],
                $row['instructions'],
                CategoryQueryHolder::getCategoriesForRecipe($row['id']),
                $row['id'],
                $row['datetime']
            );

            array_push($recipes, $recipe);
        }

        return $recipes;
    }

    /**
     * Gets all recipes in the database and paginates them
     * @param int $pagination_limit The number of recipes per page
     * @param string query The query string
     * @param array ...$params The query parameters 
     * @return [int, Generator<Models\Recipe[]>] The count and generator for the paginated recipes
     */
    public static function getAllPaginated($pagination_limit, $filtering_params)
    {
        $query_components = QueryHolder::buildFilteredQuery($filtering_params);
        $results = PaginationQueryHolder::getPaginated($pagination_limit, $query_components['query'], ...$query_components['params']);

        function generator($results)
        {
            foreach ($results['generator'] as $raw_results_page) {
                yield array_map(function ($row) {
                    $recipe = new Recipe(
                        $row['name'],
                        $row['difficulty'],
                        $row['prep_time_minutes'],
                        $row['image_url'],
                        $row['instructions'],
                        CategoryQueryHolder::getCategoriesForRecipe($row['id']),
                        $row['id'],
                        $row['datetime']
                    );

                    return $recipe;
                }, $raw_results_page);
            }
        }

        return [
            'count' => $results['count'],
            'generator' => generator($results)
        ];
    }

    /**
     * Gets a recipe by its id
     * @param int $id The id of the recipe
     * @return Models\Recipe The retrieved recipe
     */
    public static function getById($id)
    {
        $result = DB::query('SELECT * FROM recipes WHERE id = %d', $id);

        if (count($result) === 0) return null;

        $result = $result[0];

        return new Recipe(
            $result['name'],
            $result['difficulty'],
            $result['prep_time_minutes'],
            $result['image_url'],
            $result['instructions'],
            CategoryQueryHolder::getCategoriesForRecipe($result['id']),
            $result['id'],
            $result['datetime']
        );
    }

    /**
     * Inserts a recipe into the database
     * @param Models\Recipe $recipe The recipe to insert
     * @return void
     */
    public static function insert($recipe)
    {
        unset($recipe->id);
        unset($recipe->datetime);
        $categories = $recipe->categories;
        unset($recipe->categories);

        DB::insert('recipes', (array)$recipe);
        $recipe_id = DB::insertId();

        foreach ($categories as $category) {
            // Create the category if it doesn't exist yet
            CategoryQueryHolder::insertIgnore($category);

            $category_id = DB::query('SELECT id FROM categories WHERE name = %s', $category->name)[0]['id'];

            // Create a relation row in the junction table
            DB::insert('recipes_categories', [
                'recipe_id' => $recipe_id,
                'category_id' => $category_id
            ]);
        }
    }

    /**
     * Gets the total number of recipes in the database
     * @return int The total number of recipes
     */
    public static function getTotalRecipeCount()
    {
        return DB::query('SELECT COUNT(*) FROM recipes')[0]['COUNT(*)'];
    }

    /**
     * Builds the query and parameters for a filtered, paginated recipe query
     * @param array $filtering_params The filtering parameters
     * @return array An associative array containing both the query and parameters
     */
    protected static function buildFilteredQuery($filtering_params)
    {
        // Validate category if specified
        if ($filtering_params['category'] && !CategoryQueryHolder::isCategoryIdValid($filtering_params['category'])) {
            $filtering_params['category'] = null;
        }

        // Build the query
        $query = $filtering_params['category'] ? 'SELECT r.* FROM recipes r INNER JOIN recipes_categories c ON r.id = c.recipe_id' : 'SELECT * FROM recipes';
        $params = [];

        $filters = $filtering_params;
        unset($filters['order_by']);

        $first_filter_added_to_query = false;

        foreach ($filters as $key => $value) {
            if ($value === null)
                continue;

            array_push($params, $value);

            if (!$first_filter_added_to_query) {
                $query .= ' WHERE ';
                $first_filter_added_to_query = true;
            } else {
                $query .= ' AND ';
            }

            switch ($key) {
                case 'category':
                    $query .= 'c.category_id = %d';
                    break;
                default:
                    $query .= $key . ' = %s';
            }
        }

        $query .= ' ORDER BY %b %l';
        array_push($params, $filtering_params['order_by'][0], $filtering_params['order_by'][1]); // These will hold safe values since they are switched to predefined values in the parsing

        return [
            'query' => $query,
            'params' => $params
        ];
    }
}
