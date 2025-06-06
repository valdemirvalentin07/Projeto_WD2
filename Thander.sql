CREATE DATABASE Thander;
USE Thander;

create table ordem_servicos(
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(150),
telefone VARCHAR(20),
endereco VARCHAR(255),
aparelho_marca VARCHAR(100),
descricao TEXT,
status ENUM('Aberta','Em andamento','Concluída','Cancelada')
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin') NOT NULL
);

