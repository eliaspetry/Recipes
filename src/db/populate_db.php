<?php

require_once __DIR__ . '/../models/recipe.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/category.php';
require_once __DIR__ . '/../services/mysql/recipe/queries.php';
require_once __DIR__ . '/../services/mysql/user/queries.php';

use Models\Recipe;
use Models\User;
use Models\Category;
use Services\MySql\Queries\Recipe\QueryHolder as RecipeQueryHolder;
use Services\MySql\Queries\User\QueryHolder as UserQueryHolder;

// Populate the DB with the mock recipe data from the recipes JSON file

$json = json_decode(file_get_contents('recipes.json', true));

$recipes = array_map(function($recipe) {
    $parsedRecipe = new Recipe($recipe->name, $recipe->difficulty, $recipe->prep_time_minutes, $recipe->image_url, $recipe->instructions);
    $parsedRecipe->addCategories(array_map(function($category_name) {
        return new Category($category_name);
    }, $recipe->categories));
    return $parsedRecipe;
}, $json->recipes);

foreach ($recipes as $recipe) {
    RecipeQueryHolder::insert($recipe);
}

// Populate the DB with the default mock user
UserQueryHolder::insert(new User('epetry', 'epetry', 'Elias', 'Petry'));