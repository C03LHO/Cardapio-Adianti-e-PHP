# 🍽️ Sistema de Cardápio Digital Moderno

![PHP](https://img.shields.io/badge/PHP-7.4+-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![Adianti](https://img.shields.io/badge/Adianti-Framework-green)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![License](https://img.shields.io/badge/License-Open%20Source-brightgreen)

Este projeto apresenta um sistema completo e profissional para o gerenciamento de cardápios digitais, desenvolvido com o robusto **Adianti Framework** e **PHP**. Ele é ideal para uma variedade de estabelecimentos, como restaurantes, lanchonetes e pizzarias, que buscam modernizar a apresentação de seus produtos e otimizar a gestão de seus cardápios.

## ✨ Visão Geral do Projeto

O sistema oferece uma interface de usuário moderna e responsiva, garantindo uma experiência fluida em diferentes dispositivos, desde desktops até smartphones. A gestão de produtos e categorias é intuitiva, permitindo o controle detalhado do cardápio.

## 🚀 Funcionalidades Principais

### Interface do Usuário (Frontend)

- **Design Responsivo**: Adapta-se perfeitamente a qualquer tamanho de tela, proporcionando uma visualização otimizada em dispositivos móveis e desktops.

- **Visual Moderno**: Incorpora gradientes elegantes e animações suaves para uma experiência visual agradável e interativa.

- **Cards Interativos**: Produtos são exibidos em cards visuais, facilitando a navegação e a seleção.

- **Tipografia Otimizada**: Utiliza fontes modernas e legíveis para garantir clareza e estética.

### Gerenciamento (Backend)

- **Gestão de Categorias**: Permite a criação, edição e exclusão de categorias, organizando os produtos de forma hierárquica.

- **Gestão de Produtos**: Funcionalidades completas para cadastro de produtos, incluindo nome, descrição detalhada (ingredientes, características), preço, status (ativo/inativo) e upload de imagens.

- **Upload de Imagens**: Suporte para upload de imagens de produtos, com validação de tipo e tamanho de arquivo.

## 🛠️ Tecnologias Utilizadas

O projeto é construído sobre uma pilha de tecnologias modernas e eficientes:

- **PHP 7.4+**: Linguagem de programação backend.

- **MySQL 5.7+**: Sistema de gerenciamento de banco de dados relacional.

- **Adianti Framework 7.x**: Framework PHP para desenvolvimento rápido de aplicações web.

- **Bootstrap 5**: Framework CSS para desenvolvimento responsivo e mobile-first.

- **Font Awesome**: Biblioteca de ícones.

- **CSS3 Animations**: Para efeitos visuais e transições.

- **JavaScript ES6**: Para interações dinâmicas no frontend.

## ⚙️ Instalação e Configuração

Para configurar e executar o projeto em seu ambiente local, siga os passos abaixo:

1. **Clone o Repositório**: Baixe o projeto para o diretório de seu servidor web (ex: `htdocs` para Apache, `www` para Nginx).

1. **Configuração do Banco de Dados**: Edite o arquivo `app/config/cardapio.ini` com as credenciais do seu banco de dados MySQL.

1. **Criação do Banco de Dados**: Crie o banco de dados `cardapio` no MySQL.

1. **Execução do Setup**: Acesse o arquivo `setup.php` através do seu navegador para inicializar a estrutura do banco de dados e as configurações iniciais do sistema.

1. **Acesso ao Sistema**: Após a configuração, o sistema estará acessível através da seguinte URL:

## 📖 Como Usar

### Adicionando Produtos

1. No painel administrativo, clique em "Novo Produto".

1. Preencha os campos obrigatórios: Nome do produto, Categoria e Preço.

1. Opcionalmente, adicione uma Descrição e faça o upload de uma Imagem.

1. Defina o Status do produto (ativo/inativo).

1. Clique em "Salvar" para registrar o produto.

### Gerenciando Categorias

1. Para criar uma nova categoria, clique em "Nova Categoria" no painel administrativo.

1. Preencha o Nome da categoria e, opcionalmente, uma Descrição.

1. Utilize a opção "Gerenciar Categorias" para editar ou excluir categorias existentes.

### Visualizando o Cardápio

- O cardápio principal pode ser acessado através do menu "Visualizar Cardápio".

- Os produtos são automaticamente organizados por categoria.

- Cada produto exibe um botão de edição rápida para facilitar a manutenção.

## 🎨 Personalização

O sistema permite diversas customizações visuais e funcionais:

- **Estilos CSS**: Edite o arquivo `app/resources/cardapio-moderno.css` para ajustar cores, gradientes e outros elementos visuais.

- **JavaScript**: O arquivo `app/resources/cardapio-moderno.js` contém scripts para animações, efeitos de loading, notificações e lazy loading de imagens.

- **Configurações de Upload**: As configurações de upload de imagens (diretório, tipos permitidos, tamanho máximo) podem ser ajustadas no arquivo `ProdutoForm.php` (linhas 135-164).

## 🐛 Solução de Problemas Comuns

- **Imagens não aparecem**: Verifique se o diretório `app/images/produtos/` existe e possui permissões de escrita (777). Confirme se as imagens foram enviadas corretamente.

- **Erro de conexão com o banco de dados**: Revise as configurações em `app/config/cardapio.ini`. Certifique-se de que o servidor MySQL está em execução e que o banco de dados `cardapio` foi criado e populado corretamente (via `setup.php` ou `database_structure.sql`).

- **Layout quebrado**: Verifique se os arquivos CSS estão sendo carregados corretamente e se o Bootstrap está integrado. Limpe o cache do navegador.

## 📄 Licença

Este projeto é de código aberto e está disponível para uso e modificação sob a licença [MIT](https://opensource.org/licenses/MIT). Sinta-se à vontade para utilizá-lo em seus projetos pessoais ou comerciais.

---

Desenvolvido com ❤️ por Manus AI, utilizando Adianti Framework.

