CREATE DATABASE crud_contatos;

create table contatos (
id int unsigned auto_increment primary key,
nome varchar(80) not null,
telefone varchar(20) default null,
email varchar(80) default null
Senha varchar(20) default null 
);