<?php

namespace Models;

require_once __DIR__ . '/category.php';
require_once __DIR__ . '/model.php';

/**
 * Recipe DB columns/fields trait
 */
trait RecipeFields
{
    public string $name;
    public string $difficulty;
    public int $prep_time_minutes;
    public string $image_url;
    public string $instructions;
    public ?array $categories;
    public ?int $id;
    public ?string $datetime;
}

/**
 * Recipe model
 */
class Recipe extends Model
{
    use RecipeFields;

    /**
     * Recipe constructor
     * @param string $name The name of the recipe
     * @param string $difficulty The difficulty of the recipe
     * @param int $prep_time_minutes The preparation time in minutes
     * @param string $image_url The URL of the recipe's image
     * @param string $instructions The cooking instructions of the recipe
     * @param ?array $categories The categories of the recipe
     * @param ?int $id The id of the recipe
     * @param ?string $datetime The datetime stamp of the recipe
     * @return void
     */
    public function __construct($name, $difficulty, $prep_time_minutes, $image_url, $instructions, $categories = null, $id = null, $datetime = null)
    {
        $RECIPE_IMG_BASE_URL = '/pec3/src/assets/images/recipes/';

        $this->name = $name;
        $this->difficulty = $difficulty;
        $this->prep_time_minutes = $prep_time_minutes;
        $this->image_url = strpos($image_url, $RECIPE_IMG_BASE_URL) === false ? $RECIPE_IMG_BASE_URL . $image_url : $image_url;
        $this->instructions = $instructions;
        $this->id = $id;
        $this->datetime = $datetime;
        $this->categories = [];

        if (is_array($categories)) {
            $this->categories = $categories;
        }
    }

    /**
     * Get the parsed and translated difficulty of the recipe for visual representation
     * @return string
     */
    public function getParsedDifficulty()
    {
        switch (strtolower($this->difficulty)) {
            case 'easy':
                return 'Fácil';
                break;
            case 'medium':
                return 'Media';
                break;
            case 'hard':
                return 'Difícil';
                break;
            default:
                return 'Desconocida';
        }
    }

    /**
     * Get the publication date of the recipe in locale format for visual representation
     * @return string
     */
    public function getLocalePubDate()
    {
        return date('d/m/Y', strtotime($this->datetime));
    }

    /**
     * Get the shortened preview text of the recipe for visual representation
     * @param int $max_length The maximum length of the preview text
     * @param string $read_more_prompt The tail prompt to show when the preview text is shortened
     * @return string
     */
    public function getPreviewText($max_length, $read_more_prompt = '...')
    {
        $instructions = preg_split('/\s+/', $this->instructions, -1, PREG_SPLIT_NO_EMPTY);

        if (count($instructions) <= $max_length)
            return $this->instructions;

        // Limit the description, if longer, to only the max_length words
        return implode(' ', array_slice($instructions, 0, $max_length)) . $read_more_prompt;
    }

    /**
     * Get the parsed preparation time of the recipe for visual representation
     * @return string
     */
    public function getParsedPrepTime()
    {
        if ($this->prep_time_minutes === 60) return '1h';

        return $this->prep_time_minutes < 60 ? $this->prep_time_minutes . 'm' : $this->prep_time_minutes / 60 . 'h ' . $this->prep_time_minutes % 60 . 'm';
    }

    /**
     * Get the parsed, comma-separated categories of the recipe for visual representation
     * @return string
     */
    public function getParsedCategories()
    {
        return implode(
            ', ',
            array_map(function ($category) {
                return $category->name;
            }, $this->categories)
        );
    }

    /**
     * Add categories to the recipe
     * @param \Models\Category[] $categories The categories to add
     */
    public function addCategories($categories)
    {
        $this->categories = array_merge($this->categories, $categories);
    }

    /**
     * Serialize the recipe (base class override)
     * @return array The associative array containing the serialized recipe key-values
     */
    public function serialize()
    {
        $result = parent::serialize();

        $result['difficulty'] = $this->getParsedDifficulty();
        unset($result['image_url']);

        return $result;
    }
}
