create database livraria SET utf8 COLLATE utf8_general_ci;;

use livraria;

create table categorias (
	cat_id int primary key auto_increment not null,
	descricao varchar(150)
) DEFAULT CHARACTER SET=utf8;

create table livros (
	liv_id int primary key auto_increment not null,
	titulo  varchar(150),
	descricao varchar(500),
	categoria_id int not null,
	autor varchar(100),
	ano int,
	preco decimal(10,2),
	paph varchar(150),
	badge int,
	foreign key (categoria_id) references categorias(cat_id)
) DEFAULT CHARACTER SET=utf8;

create table usuarios (
	user_id int primary key auto_increment not null,
	nome varchar(150),
	email varchar(255) not null,
	senha varchar(32) not null
) DEFAULT CHARACTER SET=utf8;

insert into categorias (descricao)
values('Ficção');
insert into categorias (descricao)
values('Auto-Ajuda');
insert into categorias (descricao)
values('Romance');
insert into categorias (descricao)
values('Acadêmico');
insert into categorias (descricao)
values('Não-Ficção');
insert into categorias (descricao)
values('Informática');
insert into categorias (descricao)
values('Engenharia');
insert into categorias (descricao)
values('Poesia');
