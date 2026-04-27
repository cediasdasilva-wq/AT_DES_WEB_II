CREATE DATABASE IF NOT EXISTS produtos_db;
USE produtos_db;

CREATE TABLE IF NOT EXISTS produtos (
    descricao VARCHAR(255) PRIMARY KEY,
    categoria VARCHAR(100) NOT NULL,
    valor_venda DECIMAL(10, 2) NOT NULL,
    lucro_unitario DECIMAL(10, 2) NOT NULL,
    estoque INT NOT NULL
);
