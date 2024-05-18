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
            <section id="recetas">
                <div class="flex flex-col items-center justify-center gap-3 rounded-lg w-full my-8">
                    <?php
                        require_once __DIR__ . '/../controllers/recipe/recipe.php';

                        use Controllers\Recipe\Controller as RecipeController;

                        foreach (RecipeController::getLatestRecipes(5) as $recipe) {
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
                                    <img class="h-96 w-full object-cover object-center" src="' . $recipe->image_url . '" alt="' . $recipe->name . '">
                                </div>
                            </a>
                            ';
                        }
                    ?>
                </div>
            </section>
		</main>
        <?php require __DIR__ . '/common/footer.php'; ?>
	</body>
</html>
