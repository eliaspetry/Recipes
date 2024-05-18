<?php

require_once __DIR__ . '/../../controllers/api/recipe/recipe.php';

use Controllers\Api\Recipe\Controller as RecipeController;

header('Content-Type: application/json; charset=utf-8');
RecipeController::getRecipeById();
