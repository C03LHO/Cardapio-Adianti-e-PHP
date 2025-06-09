-- ==================================================
--   Script de Correção do Banco de Dados - Cardápio
-- ==================================================

-- Criar banco se não existir
CREATE DATABASE IF NOT EXISTS cardapio DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
USE cardapio;

-- Recriar tabela de categorias com estrutura correta
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;

CREATE TABLE categorias (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    status ENUM('ativo','inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Recriar tabela de produtos com campo imagem
CREATE TABLE produtos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    categoria_id INT(11) NOT NULL,
    imagem VARCHAR(255) DEFAULT NULL,
    status ENUM('ativo','inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir dados de exemplo
INSERT INTO categorias (nome, descricao, status) VALUES
('Entradas', 'Deliciosas opções para começar sua refeição', 'ativo'),
('Pratos Principais', 'Nossos pratos principais que vão satisfazer sua fome', 'ativo'),
('Sobremesas', 'Doces tentadores para finalizar sua refeição', 'ativo'),
('Bebidas', 'Bebidas refrescantes e saborosas', 'ativo');

INSERT INTO produtos (nome, descricao, preco, categoria_id, status) VALUES
('Bruschetta Italiana', 'Pão italiano tostado com tomate, manjericão e alho', 15.90, 1, 'ativo'),
('Bolinhos de Bacalhau', 'Tradicionais bolinhos portugueses com bacalhau desfiado', 18.50, 1, 'ativo'),
('Filé de Salmão Grelhado', 'Filé de salmão grelhado com ervas finas e legumes', 45.90, 2, 'ativo'),
('Risotto de Camarão', 'Cremoso risotto com camarões frescos e açafrão', 38.50, 2, 'ativo'),
('Picanha na Brasa', 'Suculenta picanha grelhada na brasa com acompanhamentos', 52.90, 2, 'ativo'),
('Tiramisu', 'Clássica sobremesa italiana com mascarpone e café', 16.90, 3, 'ativo'),
('Petit Gateau', 'Bolinho de chocolate quente com sorvete de baunilha', 19.50, 3, 'ativo'),
('Suco Natural', 'Sucos naturais de frutas da estação', 8.90, 4, 'ativo'),
('Caipirinha', 'Tradicional caipirinha brasileira', 12.50, 4, 'ativo'),
('Refrigerante', 'Refrigerantes diversos 350ml', 5.50, 4, 'ativo');

-- Verificar se as tabelas foram criadas corretamente
SELECT 'Categorias criadas:' AS status, COUNT(*) as total FROM categorias;
SELECT 'Produtos criados:' AS status, COUNT(*) as total FROM produtos;

-- Mostrar estrutura das tabelas
SHOW COLUMNS FROM categorias;
SHOW COLUMNS FROM produtos;

