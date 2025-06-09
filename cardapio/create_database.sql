-- Script para criar o banco de dados do Cardápio Digital
-- Execute este script no MySQL antes de executar o setup.php

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS `cardapio` 
DEFAULT CHARACTER SET utf8mb4 
DEFAULT COLLATE utf8mb4_unicode_ci;

-- Usar o banco criado
USE `cardapio`;

-- Criar usuário específico (opcional)
-- CREATE USER IF NOT EXISTS 'cardapio_user'@'localhost' IDENTIFIED BY 'senha_segura';
-- GRANT ALL PRIVILEGES ON cardapio.* TO 'cardapio_user'@'localhost';
-- FLUSH PRIVILEGES;

-- Verificar se foi criado
SELECT 'Banco de dados cardapio criado com sucesso!' as status;

-- Para usar este script:
-- 1. Abra o MySQL Workbench ou linha de comando
-- 2. Execute este script
-- 3. Depois execute o setup.php no navegador

