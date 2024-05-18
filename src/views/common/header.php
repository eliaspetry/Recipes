<header>
	<div class="flex flex-row items-center justify-center gap-3 py-6">
		<img src="/pec3/src/assets/images/logo.png" height="75" width="75" alt="logo" />
		<h1 class="text-6xl font-bold text-teal-500">Recetas</h1>
	</div>
	<?php
		session_start();

		if (isset($_SESSION['user_data'])) {
			echo '<p class="text-md text-gray-600 text-center mb-10">¡Bienvenido/a, <strong>' . $_SESSION['user_data']['username'] . '!</strong></p>';
		}
	?>
	<nav>
		<ul class="flex flex-col items-center justify-center gap-5 md:flex-row">
			<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
				<a href="/pec3/src/views/index.php">Home</a>
			</li>
			<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
				<a href="/pec3/src/views/activity_2.php" target="_blank">Act2</a>
			</li>
			<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
				<a href="/pec3/src/views/recipes.php?page=1">Recetas</a>
			</li>
			<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
				<a href="/pec3/src/views/api/recipes.php/1" target="_blank">API_recetas</a>
			</li>
			<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
				<a href="/pec3/src/views/api/recipe.php/1" target="_blank">API_receta</a>
			</li>
			<?php
			if (!isset($_SESSION['user_data'])) {
				echo '
				<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
					<a href="/pec3/src/views/login.php">Login</a>
				</li>
				<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
					<a href="/pec3/src/views/signup.php">Signup</a>
				</li>
				';
			} else {
				echo '
				<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
					<a href="/pec3/src/views/profile.php">Perfil de usuario</a>
				</li>
				<li class="p-3 bg-teal-300 font-bold text-teal-800 rounded-md cursor-pointer w-5/6 md:w-auto text-center">
					<a href="/pec3/src/views/gateways/logout_gateway.php">Logout</a>
				</li>
				';
			}
			?>
		</ul>
	</nav>
</header>
