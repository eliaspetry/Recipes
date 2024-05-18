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
        <section id="signup">
            <div class="flex flex-col items-center justify-center w-full my-10">
                <div class="flex flex-col items-center justify-center gap-5 px-5 py-10 bg-purple-100 rounded-lg w-5/6 max-w-5xl">
                    <h1 class="text-4xl font-bold text-rose-400 text-center">Perfil de usuario</h1>
                    <img src="/pec3/src/assets/images/chef.png" alt="chef" class="w-32 h-32 mb-5">
                    <div class="flex flex-col items-center justify-center gap-3 w-full">
                        <p class="font-bold text-center">Nombre de usuario</p>
                        <p class="px-5 py-2 bg-violet-200 rounded-md w-full max-w-xl text-center"><?= $_SESSION['user_data']['username']; ?></p>
                        <p class="font-bold text-center">Nombre y apellidos</p>
                        <p class="px-5 py-2 bg-violet-200 rounded-md w-full max-w-xl text-center"><?= $_SESSION['user_data']['name'] . ' ' . $_SESSION['user_data']['surname']; ?></p>
                        <?php
                        if (isset($_GET['error'])) {
                            echo '<p class="text-red-500 font-bold text-center">Error al cambiar la contraseña: por favor, revisa los campos</p>';
                        }
                        ?>
                        <form action="/pec3/src/views/gateways/change_password_gateway.php" method="POST" class="flex flex-col items-center justify-center gap-5 w-5/6 max-w-5xl mt-5">
                            <label for="new_password" class="font-bold">Nueva contraseña</label>
                            <input type="password" name="new_password" placeholder="Elige tu nueva contraseña" class="input w-full max-w-xl p-3 text-center" minlength="6" maxlength="64" required>
                            <label for="confirm_new_password" class="font-bold">Confirmar contraseña</label>
                            <input type="password" name="confirm_new_password" placeholder="Confirma tu nueva contraseña" class="input w-full max-w-xl p-3 text-center" minlength="6" maxlength="64" required>
                            <input type="submit" value="Cambiar contraseña" class="button w-full max-w-xl p-3 font-bold bg-rose-700 text-white rounded-md cursor-pointer mt-10 hover:bg-rose-600">
                        </form>
                    </div>
                </div>
        </section>
    </main>
    <?php require __DIR__ . '/common/footer.php'; ?>
</body>

</html>