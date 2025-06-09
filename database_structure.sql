-- ==================================================
--   Estrutura do Banco de Dados - Cardápio Digital
-- ==================================================

-- Tabela de categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `preco` decimal(10,2) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_categoria` (`categoria_id`),
  CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir categorias de exemplo
INSERT IGNORE INTO `categorias` (`id`, `nome`, `descricao`, `status`) VALUES
(1, 'Entradas', 'Deliciosas opções para começar sua refeição', 'ativo'),
(2, 'Pratos Principais', 'Nossos pratos principais que vão satisfazer sua fome', 'ativo'),
(3, 'Sobremesas', 'Doces tentadores para finalizar sua refeição', 'ativo'),
(4, 'Bebidas', 'Bebidas refrescantes e saborosas', 'ativo');

-- Inserir produtos de exemplo
INSERT IGNORE INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `categoria_id`, `status`) VALUES
(1, 'Bruschetta Italiana', 'Pão italiano tostado com tomate, manjericão e alho', 15.90, 1, 'ativo'),
(2, 'Bolinhos de Bacalhau', 'Tradicionais bolinhos portugueses com bacalhau desfiado', 18.50, 1, 'ativo'),
(3, 'File de Salmao Grelhado', 'File de salmão grelhado com ervas finas e legumes', 45.90, 2, 'ativo'),
(4, 'Risotto de Camarão', 'Cremoso risotto com camarões frescos e açafrão', 38.50, 2, 'ativo'),
(5, 'Picanha na Brasa', 'Suculenta picanha grelhada na brasa com acompanhamentos', 52.90, 2, 'ativo'),
(6, 'Tiramisu', 'Clássica sobremesa italiana com mascarpone e café', 16.90, 3, 'ativo'),
(7, 'Petit Gateau', 'Bolinho de chocolate quente com sorvete de baunilha', 19.50, 3, 'ativo'),
(8, 'Suco Natural', 'Sucos naturais de frutas da estação', 8.90, 4, 'ativo'),
(9, 'Caipirinha', 'Tradicional caipirinha brasileira', 12.50, 4, 'ativo'),
(10, 'Refrigerante', 'Refrigerantes diversos 350ml', 5.50, 4, 'ativo');

-- Adicionar campo imagem se não existir (para atualizações)
SET @sql = (
    SELECT CASE 
        WHEN COUNT(*) = 0 THEN 'ALTER TABLE produtos ADD COLUMN imagem VARCHAR(255) DEFAULT NULL AFTER categoria_id'
        ELSE 'SELECT "Campo imagem já existe" AS status'
    END
    FROM information_schema.columns 
    WHERE table_name = 'produtos' 
    AND column_name = 'imagem'
    AND table_schema = DATABASE()
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Índices para melhor performance
CREATE INDEX IF NOT EXISTS idx_produtos_categoria ON produtos(categoria_id);
CREATE INDEX IF NOT EXISTS idx_produtos_status ON produtos(status);
CREATE INDEX IF NOT EXISTS idx_categorias_status ON categorias(status);

-- Visualização para relatórios
CREATE OR REPLACE VIEW vw_produtos_completo AS
SELECT 
    p.id,
    p.nome as produto_nome,
    p.descricao as produto_descricao,
    p.preco,
    p.imagem,
    p.status as produto_status,
    c.id as categoria_id,
    c.nome as categoria_nome,
    c.descricao as categoria_descricao,
    p.created_at,
    p.updated_at
FROM produtos p
INNER JOIN categorias c ON p.categoria_id = c.id;

COMMIT;

