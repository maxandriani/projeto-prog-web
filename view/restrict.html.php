<?php
require 'view/partial/header.html.php'; ?>

		<!-- bootstrap -->
		<div class="container">
			<div class="row">
				<div class="col-sm-8">
					<!-- filter -->
					<form name="search-form" id="searchForm">
						<div class="input-group">
							<input type="text" name="search" class="form-control" placeholder="Pesquisar ...">
							<span class="input-group-btn">
								<button class="btn btn-default glyphicon glyphicon-search" type="submit"></button>
							</span>
						</div><!-- /input-group -->
					</form>
					<br />
					
					<!-- cards area -->
					<div class="library-grid"></div> <!-- // cards area -->
				</div>

				<div class="col-sm-4">
					<!-- form -->
					<div class="panel panel-default panel-register-book">
						<div class="panel-heading">Cadastrar livro</div>
						<div class="panel-body">
							<form name="inserForm" id="insertForm" enctype="multipart/form-data">
								<fieldset>
									<legend>Cadastro de livros</legend>

									<!-- title -->
									<div class="form-group">
										<label for="fieldTitle">Título: </label>
										<input type="text" class="form-control" id="fieldTitle" name="title" />
									</div> <!-- // title -->

									<!-- description -->
									<div class="form-group">
										<label for="fieldDescription">Descrição: </label>
										<textarea id="fieldDescription" class="form-control" name="description"></textarea>
									</div> <!-- // description -->

									<!-- category -->
									<div class="form-group">
										<label for="fieldCategory">Categoria: </label>
										<select id="fieldCategory" class="form-control" name="category_id">
											<option value="">Carregando</option>
										</select>
									</div> <!-- // category -->

									<!-- author -->
									<div class="form-group">
										<label for="fieldAuthor">Autor: </label>
										<input type="text" id="fieldAuthor" class="form-control" name="author" />
									</div> <!-- // author -->

									<!-- year -->
									<div class="form-group">
										<label for="fieldYear">Ano: </label>
										<input type="number" id="fieldYear" class="form-control" name="year" />
									</div> <!-- // year -->

									<!-- price -->
									<div class="form-group">
										<label for="fieldPrice">Preço: </label>
										<input type="number" step="0.01" id="fieldPrice" class="form-control" name="price" />
									</div> <!-- // price -->

									<!-- cover -->
									<div class="form-group">
										<label for="fieldCover">Capa: </label>
										<input type="file" id="fieldCover" class="form-control" name="cover" />
									</div> <!-- // cover -->

									<!-- submit -->
									<div class="submit-group">
										<button type="submit" class="btn btn-primary">Cadastrar</button>
									</div> <!-- // submit -->
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div> <!-- // bootstrap -->

<?php
require 'view/partial/footer.html.php'; ?>