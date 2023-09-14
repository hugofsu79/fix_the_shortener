<?php

require '../config.php';

session_start();

// demande de logout
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION);
}

// tentative de login
if (isset($_POST['username']) && isset($_POST['password'])) {

	$login = $_POST['username'];

	// mot de passe : version de base (chiffrage md5, insuffisant)
	$hashedPassword = md5($_POST['password']);

	//version de base
	$sql = "select * from users where login = '$login' and password = '$hashedPassword'";
	$query = $database->query($sql, PDO::FETCH_ASSOC);

	$users = $query->fetchAll();

	if (isset($users[0])) {
		$_SESSION['user'] = $users[0];
	} else {
		$loginError = true;
	}
}

?>
<html>

<head>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,200&display=swap" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
		}

		body,
		html {
			height: 100%;
		}

		.logo {
			height: 120px;
			display: block;
			margin: 0 auto;
		}

		body {
			font-family: 'Source Sans Pro', sans-serif;
			background: #edeff1;
		}

		.background {
			position: fixed;
			perspective: 200px;
			top: 100%;
			transform: translateY(-59%) scale(1.5);
			width: 100%;
			text-align: center;
			opacity: .2;
			pointer-events: none;
			perspective-origin: center;
			z-index: -1;

		}

		.background img {
			width: 100%;
			transform: rotateX(32deg);
		}

		.container {
			margin: 0 auto;
			background: white;
			border-radius: 4px;
			box-shadow: 2px 2px 7px 0px #00000029;
			padding: 5px;
			width: -moz-fit-content;
			width: fit-content;
			margin-bottom: 50px;
		}

		a {
			color: inherit;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		table td,
		table th {
			padding: 5px 8px;
		}

		table tbody tr:nth-child(odd) {
			background: #f00;
			background: #dcdcdc;
		}

		label,
		input,
		button {
			display: block;
		}

		input,
		button {
			font-size: 17px;
			line-height: 30px;
			padding: 7px 15px;
			border: 1px solid;
			border-radius: 4px;
			margin: 1em;
			width: 350px;
			background: transparent;
			outline: none;
		}

		button {
			background: #000;
			color: #fff;
			border-radius: 4px;
		}

		hr {
			border: 1px solid #d4d4d4;
			border-bottom: none;
			margin: 15px 15px;
		}

		p {
			margin: 7px 15px;
		}

		.error {
			padding: 7px 15px;
			background: #ffa8a8;
			border: 1px solid;
			color: #904747;
			border-radius: 4px;
		}
	</style>
</head>

<body>
	<div class="background"><img src="/img/background.svg"></div>

	<a href="/"><img src="/img/logo.png" class="logo"></a>

	<div class="container">
		<?php if (isset($_SESSION['user'])) : ?>

			<p>
				Bonjour <?= $_SESSION['user']['login'] ?>
				<a style="float:right;" href="?logout">Logout</a>
			</p>
			<hr>
			<?php if (!$_SESSION['user']['admin']) : ?>
				<p class="error">Vous devez être administrateur pour afficher les liens.</p>
			<?php else : ?>
				<table>
					<thead>
						<td>Target</td>
						<td>Code</td>
					</thead>
					<tbody>
						<?php
						$query = $database->query('select * from urls');
						foreach ($query->fetchAll() as $url) : ?>
							<tr>
								<td>
									<a href="<?= $url['target'] ?>" target="_blank">
										<?= $url['target'] ?>
									</a>
								</td>
								<td><?= $url['code'] ?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif; ?>
		<?php else : ?>
			<?php if (isset($loginError)) : ?>
				<p class="error">Login error, please try again.</p>
			<?php endif; ?>
			<form action="" method="post">
				<input type="text" name="username" placeholder="Username">
				<input type="password" name="password" placeholder="Password">

				<button>Login</button>
			</form>
		<?php endif; ?>
	</div>
</body>

</html>