<!DOCTYPE>
<html>
	<head>
		<title>Livraria Virtual</title>
		<link rel="stylesheet" href="assets/bootstrap-3.3.7/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/css/livraria.css" />
	</head>
	<body>
		<!-- header -->
		<header>
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">Livraria Virtual</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<?php if (AccountsController::is_user_logged_in()): ?>
							<li class="active"><a href="#logout" id="doLogout">Sair</a></li>
							<?php endif; ?>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
		</header> <!-- // header -->

		<!-- notifications -->
		<div class="notifications-area">
			<div class="container"></div>
		</div> <!-- // notifications -->