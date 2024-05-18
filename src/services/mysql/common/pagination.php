<?php

namespace Services\MySql\Queries\Common\Pagination;

require_once __DIR__ . '/../connector.php';

use DB;

/**
 * Common pagination queries
 */
class QueryHolder
{
    /**
     * Base function for a paginated query
     * @param int $pagination_limit The number of rows to return per page
     * @param string $query The query
     * @param array ...$params The query parameters to bind into the base query
     * @return [int, Generator<\Models\Recipe[]>] The total count of results and the paginated results
     */
    public static function getPaginated($pagination_limit, $query, ...$params)
    {
        $results = DB::query($query, ...$params);

        function generator($results, $pagination_limit)
        {
            $buffer = [];

            // If there are no results, yield and empty array and return immediately
            if (!$results) {
                yield [];
                return;
            }

            foreach ($results as $row) {
                array_push($buffer, $row);

                // If the buffer has reached the pagination limit, return the accumulated rows and reset it
                if (count($buffer) === $pagination_limit) {
                    yield $buffer;
                    $buffer = [];
                }
            }

            // Yield any residual rows below the limit for the last pagination page
            if (count($buffer) > 0) {
                yield $buffer;
            }
        }

        return [
            'count' => !$results ? 0 : count($results),
            'generator' => generator($results, $pagination_limit)
        ];
    }
}
