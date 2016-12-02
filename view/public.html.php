<?php
include 'view/partial/header.html.php'; ?>

		<!-- the form -->
		<div class="insert-form-area">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">

						<!-- login panel -->
						<div class="panel panel-primary">
							<div class="panel-heading">Já possuo cadastro</div>
							<div class="panel-body">
								
								<!-- loggin -->
								<form name="loginForm" id="loginForm">
									<fieldset>										
										<!-- title -->
										<div class="form-group">
											<label for="loginEmail">Email: </label>
											<input type="email" class="form-control" id="LoginEmail" name="email" />
										</div> <!-- // title -->

										<!-- description -->
										<div class="form-group">
											<label for="loginPass">Senha: </label>
											<input type="password" class="form-control" id="loginPass" name="pass" />
										</div> <!-- // description -->

										<!-- submit -->
										<div class="submit-group">
											<button type="submit" class="btn btn-primary">Acessar</button>
										</div> <!-- // submit -->
									</fieldset>
								</form> <!-- // login -->
							</div>
						</div> <!-- // panel -->
					</div>
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">Quero me cadastrar</div>
							<div class="panel-body">
								<form name="registerForm" id="registerForm">
									<fieldset>
										<legend>Quero me cadastrar</legend>
										
										<!-- title -->
										<div class="form-group">
											<label for="registerName">Nome: </label>
											<input type="text" class="form-control" id="registerName" name="name" />
										</div> <!-- // title -->

										<!-- title -->
										<div class="form-group">
											<label for="registerEmail">Email: </label>
											<input type="email" class="form-control" id="registerEmail" name="email" />
										</div> <!-- // title -->

										<!-- description -->
										<div class="form-group">
											<label for="regiterPass">Senha: </label>
											<input type="password" class="form-control" id="registerPass" name="pass" />
										</div> <!-- // description -->

										<!-- submit -->
										<div class="submit-group">
											<button type="submit" class="btn btn-default">Cadastrar</button>
										</div> <!-- // submit -->
									</fieldset>
								</form> <!-- // login -->
							</div>
						</div> <!-- // panel -->
					</div>
				</div>
			</div> <!-- // bootstrap -->
		</div>

<?php
include 'view/partial/footer.html.php'; ?>