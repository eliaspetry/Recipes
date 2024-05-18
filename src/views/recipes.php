<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recetas</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/favicon.ico">
    <?php
    require_once __DIR__ . '/common/libraries.php';
    ?>
</head>

<body class="bg-teal-50 flex flex-col h-screen">
    <?php require './common/header.php'; ?>
    <main class="grow">
        <?php
        require_once __DIR__ . '/../controllers/recipe/recipe.php';
        require_once __DIR__ . '/../controllers/category/category.php';

        use Controllers\Recipe\Controller as RecipeController;
        use Controllers\Category\Controller as CategoryController;

        $paginated_recipes = RecipeController::getPaginatedRecipes(5);
        ?>
        <section id="pagination">
            <div class="flex flex-col items-center justify-center">
                <div class="flex flex-col items-center justify-center mt-10 gap-5 bg-teal-100 max-w-5xl w-full py-5">
                    <?php
                    $current_page = RecipeController::$state['pagination']['current_page'];
                    $total_page_count = RecipeController::$state['pagination']['total_page_count'];
                    $total_recipe_count = RecipeController::$state['pagination']['total_recipe_count'];

                    $params = array();
                    parse_str($_SERVER['QUERY_STRING'], $params);

                    if ($total_recipe_count && $total_page_count > 1) {
                        echo '<div class="flex flex-row items-center justify-center gap-3 w-full">';

                        if ($current_page > 1) {
                            $params['page'] = $current_page - 1;

                            echo '
                                <button
                                    class="bg-teal-600 font-bold text-white rounded-md cursor-pointer w-5/6 md:w-auto text-center px-5 py-2"
                                    onclick="location.href=\'recipes.php?' . http_build_query($params) . '\' "
                                >
                                    Anterior
                                </button>
                            ';
                        }

                        if ($current_page < $total_page_count) {
                            $params['page'] = $current_page + 1;

                            echo '
                                <button
                                    class="bg-teal-600 font-bold text-white rounded-md cursor-pointer w-5/6 md:w-auto text-center px-5 py-2"
                                    onclick="location.href=\'recipes.php?' . http_build_query($params) . '\'"
                                >
                                    Siguiente
                                </button>
                            ';
                        }

                        echo '</div>';
                    }
                    ?>
                    <div>
                        <p class="text-md text-gray-600 text-center font-bold">Página <?= $current_page ?> de <?= $total_page_count ?></p>
                    </div>
                </div>
            </div>
        </section>
        <section id="recetas">
            <div class="flex flex-row flex-wrap items-center justify-center gap-3 w-full my-8">
                <div class="flex flex-col items-center justify-center gap-3 my-8">
                    <p>Ordenar por:</p>
                    <select id="entry-order-by" class="input w-5/6 md:w-auto p-3 text-center">
                        <?php
                        $options = [
                            'name_asc' => 'Nombre (Ascendente)',
                            'name_desc' => 'Nombre (Descendente)',
                            'difficulty_asc' => 'Nivel de dificultad (Ascendente)',
                            'difficulty_desc' => 'Nivel de dificultad (Descendente)',
                        ];

                        $selected = isset($_GET['order_by']) ? $_GET['order_by'] : 'name_asc';

                        foreach ($options as $key => $value) {
                            echo '<option value="' . $key . '"' . ($selected === $key ? ' selected' : '') . '>' . $value . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="flex flex-col items-center justify-center gap-3 my-8">
                    <p>Filtrar por dificultad:</p>
                    <select id="entry-difficulty-filter" class="input w-5/6 md:w-auto p-3 text-center">
                        <?php
                        $options = [
                            'all' => 'Todas',
                            'easy' => 'Fácil',
                            'medium' => 'Media',
                            'hard' => 'Difícil',
                        ];

                        $selected = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'all';

                        foreach ($options as $key => $value) {
                            echo '<option value="' . $key . '"' . ($selected === $key ? ' selected' : '') . '>' . $value . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="flex flex-col items-center justify-center gap-3 my-8">
                    <p>Filtrar por categoría:</p>
                    <select id="entry-category-filter" class="input w-5/6 md:w-auto p-3 text-center">
                        <?php
                        $selected_category = isset($_GET['category']) ? $_GET['category'] : 'all';

                        echo '<option value="all"' . ($selected_category === 'all' ? ' selected' : '') . '>Todas</option>';

                        foreach (CategoryController::getCategories() as $category) {
                            echo '<option value="' . $category->id . '"' . ($selected_category === (string)$category->id ? ' selected' : '') . '>' . $category->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center gap-3 w-full my-8">
                <?php
                if (!$total_recipe_count) {
                    echo '<p class="text-xl font-bold text-gray-600 text-center">No se han encontrado recetas con estos criterios</p>';
                } else {
                    foreach ($paginated_recipes as $recipe) {
                        echo '
                            <a href="/pec3/src/views/post.php/' . $recipe->id . '" class="w-5/6 max-w-5xl">
                                <div class="receta flex flex-col items-center justify-center gap-3 p-6 bg-rose-100 rounded-lg shadow-lg my-3">
                                    <h2 class="text-4xl font-bold text-rose-400 text-center">' . $recipe->name . '</h1>
                                    <p class="text-sm text-gray-600 text-center"><span class="font-bold">Fecha de publicación:</span> ' . $recipe->getLocalePubDate() . '</p">
                                    <hr></hr>
                                    <p class="text-lg text-center"><span class="font-bold">Tiempo de preparación:</span> ' . $recipe->getParsedPrepTime() . '</p">
                                    <p class="text-lg text-center"><span class="font-bold">Nivel de dificultad:</span> ' . $recipe->getParsedDifficulty() . '</p">
                                    <hr></hr>
                                    <p class="text-lg w-full">' . $recipe->getPreviewText(30) . '</p">
                                    <img class="h-96 w-full object-cover object-center"src="' . $recipe->image_url . '" alt="' . $recipe->name . '">
                                </div>
                            </a>
                        ';
                    }
                }

                ?>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/common/footer.php'; ?>
    <script src="/pec3/src/js/recipes_filtering.js"></script>
</body>

</html>