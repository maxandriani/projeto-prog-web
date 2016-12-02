(function($){ // nom obstrutive JS

	/*****************************************************
	 * Biblioteca comum
	 */

	// Função responsável por criar uma conexão ajax
	function connect(method, path, data){
	
		var conn = {
			method: method, // Método da requisição
			url: path, // Url que será chamada
			data: data, // Informações a serem transmitidas
			dataType: 'json', // Tipo de resposta aceita
			contentType: false, // Habilita envio de arquivos por AJAX
			processData: (data instanceof FormData)? false : true // Evita que o jQuery tente validar os dados do objeto FormData
		};
		return $.ajax(conn); // Invoca método .ajax do jQuery e retorna o retorno no ajax
	};

	// Cria uma notificação de erro na interface
	function notify_error(err){
		// Se err for um objeto do tipo Error, então
		if (err instanceof Error){
			build_notification('danger', err.message);
		} else {
			// Caso contrário, imprime o que veio na resposta
			for(var x in err.responseJSON.errors){
				build_notification('danger', err.responseJSON.errors[x]);
			}
		}	
	};

	// Cria uma notificação de sucesso
	function notify_success(message){
		build_notification('success', message);
	};

	// Cria uma notificação genérica
	function build_notification(level, message){
		// Cria um objeto HTML para a notificação
		var notification = $(`<div class="alert alert-${level}" role="alert"></div>`);
		// Cria um objeto HTML para o botão de fechar
		var close = $('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		// Adiciona um evento no botão fechar
		close.on('click', function(){
			notification.remove(); // Quando clicado, esse botão removerá a notificação que gerou o clique
		});

		// Adiciona o botão dentro do objeto de notificação
		notification.append(close);
		// Adiciona a mensagem ao objeto de notificação
		notification.append(`<span>${message}</span>`);
		// Adiciona o objeto de notificação no HTML da página
		$('.notifications-area .container').append( notification );
	};

	/******************************************************************
	 * Contas de usuário
	 */

	// Autentica usuário
	function auth_user(e){
		e.preventDefault(); // Impede o comportamento nativo do evento

		var data = new FormData(this); // Parseia os dados do formulário

		connect('POST', 'index.php?q=/account/login', data) // Faz requisição
			.done(function(response){ // Se der certo, então
				document.location.reload(); // Atualiza página
			})
			.fail(notify_error); // Se der falha, chama notify_error;
	};

	// encerra sessão
	function logout_user(e){
		e.preventDefault();

		connect('POST', 'index.php?q=/account/logout', {})
			.done(function(response){
				document.location.reload();
			})
			.fail(notify_error);
	}

	// Registra novo usuário
	function register_user(e){
		e.preventDefault(); // Impere o comportamento nativo do evento

		var data = new FormData(this); // Parseia os dados do formulário
		var _self = this; // Cria alias para a global this
	
		connect('POST', 'index.php?q=/account', data) // Faz requisição
			.done(function(response){ // Se der certo, então
				notify_success('Usuário cadastrado com sucesso'); // Notifica usuário
				_self.reset(); // Limpa o formulário;
			})
			.fail(notify_error); // Se der erro, chama notify_error
	};

	/**********************************************************************
	 * Biblioteca
	 */
	
	// Cadastra um novo livro
	function register_books(e){
		e.preventDefault();

		var _self = this;
		var data = new FormData(this);
		
		connect('POST', 'index.php?q=/books', data)
			.done(function(responsse){
				notify_success('Livro cadastrado com sucesso');
				$(_self).trigger('reset');
				load_books();
			})
			.fail(notify_error);
	};

	// Carrega a lista de livros
	function load_books( search ){
		return connect('GET', 'index.php?q=/books', { search: search })
			.done(function(response){
				// Procura o elemento container (.library-grid)
				var grid = $(".library-grid");
				// Remove todo o conteúdo dentro dele;
				grid.html('');
				// Para cada registro encontrado, cria um card
				for (var x in response.data){
					var book = response.data[x];
					var card = build_book_card( book );
					// esconde o card visualmente
					card.hide();
					grid.append(card);
					// revela o card somente após inserido
					card.fadeIn();
				}
			})
			.fail(notify_error);
	};

	// Cria um card html com as informações do livro
	function build_book_card( book ){
		// Cria o card do livro com uso de jQuery
		var card = $(`<div class="col-sm-6 col-md-4">`+
									`<div class="thumbnail">`+
										`<img src="${book.cover}" alt="${book.title}">`+
										`<div class="caption hide">`+
											`<h3>${book.title}</h3>`+
											`<p>${book.description}</p>`+
											`<ul class="list-group">`+
												`<li class="list-group-item"><strong>Categoria:</strong> ${book.category}</li>`+
												`<li class="list-group-item"><strong>Autor:</strong> ${book.author}</li>`+
												`<li class="list-group-item"><strong>Ano:</strong> ${book.year}</li>`+
												`<li class="list-group-item"><strong>Preço:</strong> R$ ${book.price}</li>`+
											`</ul>`+
											`<button type="button" class="btn btn-danger">Remover</button>`+
										`</div>`+
									`</div>`+
								`</div>`);

		// Adiciona o evento de remover livro no botão remover
		card
			.find('.btn-danger')
			.on('click', function(){
				connect('DELETE', 'index.php?q=/books', {book_id: book.id}) // Solicita requisição de remoção
					.done(function(response){ // Se for removido
						notify_success('Livro removido com sucesso');
						card.remove(); // Remove também o card da interface
					})
					.fail(notify_error);
			});

		// Adiciona o evento de revelar e esconder conteúdo ao clicar na imagem
		card
			.find('img')
			.on('click', function(){
				var caption = card.find('.caption');

				if (caption.hasClass('hide')){
					caption.removeClass('hide');
				} else {
					caption.addClass('hide');
				}
			})

		return card;
	}

	// Load the full list of categories and append to #fieldCategory
	function load_categories(){
		connect('GET', 'index.php?q=/books/categories', {})
			.done(function(response){
				var cats = $('#fieldCategory');
				cats.html('');
				for (var x in response.data){
					var id = response.data[x].id;
					var description = response.data[x].description;
					cats.append(`<option value="${id}">${description}</option>`);
				}
			})
			.fail(notify_error);
	};


	/*****************************************************************************
	 * Ativa eventos
	 */

	// Quando o document terminar de ser carregado document.ready(), então
	$(document).ready(function(){
		// Verifica se existe o container dos livros, se sim, carrega os livros
		if ($('.library-grid').length > 0){
			load_books();
		}

		// Verifica se existe o container das categorias, se sim, carrega as categorias
		if ($('#fieldCategory').length > 0){
			load_categories();
		}

		// Adiciona o evento de autenticar usuparios no formulário de login
		$('#loginForm').on('submit', auth_user);
		// Adiciona o evento de logout
		$('#doLogout').on('click', logout_user);
		// Adiciona o evento de registrar usuário no formulário de registro
		$('#registerForm').on('submit', register_user);
		// Adiciona o evento de registrar novo livro no formulário de registro de livros
		$('#insertForm').on('submit', register_books);
		// Adiciona o evento de autocompletar
		$last_call = null
		$('#searchField').on('keyup', function(){
			// Verifica se existe uma chamada para load_books em andamento
			if ($last_call){
				$last_call.abort(); // Se sim, cancela ela
			}
			$last_call = load_books($(this).val()); // Cria uma nova requisição
		})

	});
})(jQuery);