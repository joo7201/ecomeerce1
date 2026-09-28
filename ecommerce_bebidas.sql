CREATE DATABASE IF NOT EXISTS ecommerce_bebidas;
USE ecommerce_bebidas;

-- Tabela de Categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de Usuários (suporta cadastro comum e Google ID)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) DEFAULT NULL,
    google_id VARCHAR(100) DEFAULT NULL,
    perfil ENUM('admin', 'cliente') DEFAULT 'cliente'
);

-- Tabela de Produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL,
    categoria_id INT,
    imagem VARCHAR(255),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabela de Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    valor_total DECIMAL(10,2) NOT NULL,
    forma_pagamento VARCHAR(50) DEFAULT 'PIX',
    status VARCHAR(50) DEFAULT 'Aguardando Pagamento',
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de Itens do Pedido
CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- Dados Iniciais (Categorias e Produtos)
INSERT INTO categorias (nome) VALUES 
('Cafés Especiais'), ('Grãos Selecionados'), ('Ervas Aromáticas e Chás')
ON DUPLICATE KEY UPDATE id=id;

INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id, imagem) VALUES 
('Café Arábica em Grãos 500g', 'Café 100% arábica com notas de chocolate e caramelo.', 45.90, 20, 1, 'https://cdn.awsli.com.br/800x800/1893/1893178/produto/120366457/2f8f7ab174974d7e861b6491e1c53a30-eifzj9c1kj.jpeg'),
('Café Bourbon Amarelo 250g', 'Grãos nobres colhidos manualmente em altitude elevada.', 38.50, 15, 1, 'cafe2.jpg'),
('Grão de Bico Orgânico 1kg', 'Grão selecionado de alta qualidade rico em proteínas.', 18.00, 30, 2, 'grao1.jpg'),
('Lentilha Canadense 500g', 'Grãos inteiros ideais para sopas e saladas nutritivas.', 12.90, 25, 2, 'grao2.jpg'),
('Erva Mate Orgânica 1kg', 'Erva pura folhada para chimarrão tradicional.', 24.90, 40, 3, 'erva1.jpg'),
('Chá Verde Natural 100g', 'Folhas selectedas para infusão revigorante.', 15.00, 50, 3, 'erva2.jpg')
ON DUPLICATE KEY UPDATE id=id;