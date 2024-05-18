<?php

namespace Controllers\Utils;

/**
 * Trait for URI route and GET parameter handling
 */
trait UriParams {
    /**
     * Get the numeric ID from the tail of the URI
     * @return int|null The numeric ID if found, else null
     */
    protected static function getNumericId() {
        // Explode the URI
        $uri_sections = explode('/', $_SERVER['REQUEST_URI']);
        $last = array_pop($uri_sections);

        if (!is_numeric($last)) {
            return null;
        }

        return (int)$last;
    }

    /**
     * Parses the GET parameters for filtering and ordering recipes / sets defaults if any of them are missing or invalid
     * @return array An associative array of the given recipe filtering and ordering parameters
     */
    protected static function getRecipeFilteringParams() {
        $filtering_params = [
            'order_by' => ['name', 'ASC'],
            'difficulty' => null,
            'category' => null
        ];

        if (isset($_GET['order_by'])) {
            switch ($_GET['order_by']) {
                case 'name_asc':
                    $filtering_params['order_by'] = ['name', 'ASC'];
                    break;
                case 'name_desc':
                    $filtering_params['order_by'] = ['name', 'DESC'];
                    break;
                case 'difficulty_asc':
                    $filtering_params['order_by'] = ['difficulty', 'ASC'];
                    break;
                case 'difficulty_desc':
                    $filtering_params['order_by'] = ['difficulty', 'DESC'];
                    break;
                default:
                    $filtering_params['order_by'] = ['name', 'ASC'];
            }
        }

        if (isset($_GET['difficulty'])) {
            switch ($_GET['difficulty']) {
                case 'easy':
                    $filtering_params['difficulty'] = 'Easy';
                    break;
                case 'medium':
                    $filtering_params['difficulty'] = 'Medium';
                    break;
                case 'hard':
                    $filtering_params['difficulty'] = 'Hard';
                    break;
                default:
                    $filtering_params['difficulty'] = null;
            }
        }

        if (isset($_GET['category'])) {
            switch ($_GET['category']) {
                case 'all':
                    $filtering_params['category'] = null;
                    break;
                default:
                    $filtering_params['category'] = $_GET['category'];
            }
        }

        return $filtering_params;
    }
}