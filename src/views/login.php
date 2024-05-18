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
        <section id="login">
            <div class="flex flex-col items-center justify-center gap-3 rounded-lg w-full my-8">
                <h1 class="text-4xl font-bold text-rose-400 text-center">Iniciar sesión</h1>
                <?php
                if (isset($_GET['error'])) {
                    echo '<p class="text-red-500 font-bold text-center">Nombre de usuario o contraseña incorrectos: por favor, revisa tus datos y prueba de nuevo</p>';
                }

                if (isset($_GET['signup_success'])) {
                    echo '<p class="text-green-500 font-bold text-center">Nuevo usuario registrado con éxito: por favor, loguéate para continuar</p>';
                }
                ?>
                <form action="/pec3/src/views/gateways/login_gateway.php" method="POST" class="flex flex-col items-center justify-center gap-5 w-5/6 max-w-5xl mt-10 px-5 py-10 bg-teal-100 rounded-lg">
                    <label for="username" class="font-bold">Nombre de usuario</label>
                    <input type="text" name="username" placeholder="Nombre de usuario" class="input w-full max-w-xl p-3 text-center">
                    <label for="password" class="font-bold">Contraseña</label>
                    <input type="password" name="password" placeholder="Contraseña" class="input w-full max-w-xl p-3 text-center">
                    <input type="submit" value="Iniciar sesión" class="button w-full max-w-xl p-3 font-bold bg-teal-700 text-white rounded-md cursor-pointer mt-10 hover:bg-teal-600">
                </form>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/common/footer.php'; ?>
</body>

</html>