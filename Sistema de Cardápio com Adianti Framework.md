# Sistema de Cardápio com Adianti Framework

Este projeto implementa um sistema de cardápio básico utilizando o Adianti Framework, com funcionalidades de CRUD para categorias e produtos, e uma listagem do cardápio por categoria.

## Requisitos

*   Servidor Web (Apache, Nginx, etc.)
*   PHP 7.4 ou superior
*   MySQL 5.7 ou superior
*   Composer (para gerenciamento de dependências do Adianti Framework)

## Instalação

Siga os passos abaixo para configurar e executar o projeto:

### 1. Clonar o Repositório (ou descompactar os arquivos)

Se você recebeu os arquivos compactados, descompacte-os em um diretório de sua preferência no seu servidor web (ex: `htdocs` do Apache).

```bash
# Exemplo: se você estiver usando Git
git clone <URL_DO_REPOSITORIO> cardapio_adianti
cd cardapio_adianti
```

### 2. Instalar Dependências do Adianti Framework

Navegue até o diretório raiz do projeto (`cardapio_adianti`) e execute o Composer para instalar as dependências do Adianti Framework. Certifique-se de ter o Composer instalado globalmente.

```bash
composer create-project adianti/framework:7.x --stability=dev --prefer-dist vendor
```

### 3. Configurar o Banco de Dados

Crie um banco de dados MySQL chamado `cardapio_db` (ou o nome que preferir, mas lembre-se de atualizar o arquivo `init.php`).

Execute o script SQL fornecido (`database.sql`) para criar as tabelas `categorias` e `produtos`.

```sql
-- Conteúdo de database.sql
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    categoria_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
```

### 4. Configurar a Conexão com o Banco de Dados

Edite o arquivo `init.php` localizado na raiz do projeto (`cardapio_adianti/init.php`).

Localize a seção `// Database configuration` e atualize as credenciais do seu banco de dados (host, nome do banco, usuário, senha).

```php
// Exemplo de configuração em init.php
TTransaction::addDatabase(
    'cardapio',
    'mysql',
    'localhost',    // Seu host do MySQL
    'cardapio_db',  // Nome do seu banco de dados
    'root',         // Seu usuário do MySQL
    'password'      // Sua senha do MySQL
);
```

### 5. Configurar o Servidor Web (Apache/Nginx)

Configure seu servidor web para apontar para o diretório `www` dentro do projeto `cardapio_adianti`.

#### Exemplo para Apache (`httpd.conf` ou arquivo de configuração de Virtual Host):

```apache
<VirtualHost *:80>
    DocumentRoot "/caminho/completo/para/cardapio_adianti/www"
    <Directory "/caminho/completo/para/cardapio_adianti/www">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Certifique-se de que o módulo `mod_rewrite` esteja habilitado no Apache.

#### Exemplo para Nginx (bloco `server`):

```nginx
server {
    listen 80;
    server_name seu_dominio.com;
    root /caminho/completo/para/cardapio_adianti/www;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock; # Verifique sua versão do PHP-FPM
    }
}
```

Após configurar o servidor web, reinicie-o.

### 6. Acessar o Sistema

Abra seu navegador e acesse a URL configurada para o seu projeto (ex: `http://localhost/cardapio_adianti` ou `http://seu_dominio.com`).

Você deverá ver a interface do sistema de cardápio. Utilize os menus para gerenciar categorias, produtos e visualizar o cardápio.


