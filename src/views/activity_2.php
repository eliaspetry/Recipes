<p style="font-family: Arial, Helvetica, sans-serif;">
    <?php
    require_once __DIR__ . '/../controllers/recipe/recipe.php';

    use Controllers\Recipe\Controller as RecipeController;

    $recipe = RecipeController::getLatestRecipes(1)[0];

    echo '<strong>Id:</strong> ' . $recipe->id . '<br>';
    echo '<strong>Datetime:</strong> ' . $recipe->getLocalePubDate() . '<br>';
    echo '<strong>Name:</strong> ' . $recipe->name . '<br>';
    echo '<strong>Difficulty:</strong> ' . $recipe->getParsedDifficulty() . '<br>';
    echo '<strong>Preparation time:</strong> ' . $recipe->getParsedPrepTime() . '<br>';
    echo '<strong>Image:</strong> ' . $recipe->image_url . '<br>';
    echo '<strong>Categories:</strong> ' . $recipe->getParsedCategories() . '<br>';
    echo '<strong>Instructions:</strong> ' . $recipe->instructions . '<br>';
    ?>
</p>