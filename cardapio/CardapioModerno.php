<?php

class CardapioModerno extends TPage
{
    private $form;
    
    public function __construct()
    {
        parent::__construct();
        
        // Adicionar CSS personalizado
        TPage::include_css('app/resources/cardapio-moderno.css');
        TPage::include_js('app/resources/cardapio-moderno.js');
        
        // Criar formulário para os botões
        $this->form = new TForm('form_cardapio_admin');
        
        $container = new TElement('div');
        $container->class = 'cardapio-container';
        
        // Header do cardápio
        $header = new TElement('div');
        $header->class = 'cardapio-header';
        $header->add('<h1><i class="fa fa-cutlery"></i> Cardápio Digital</h1>');
        $header->add('<p>Descubra nossos pratos deliciosos</p>');
        
        // Botão para adicionar produto (admin)
        $admin_panel = new TElement('div');
        $admin_panel->class = 'admin-panel';
        
        $btn_add_categoria = new TButton('btn_add_categoria');
        $btn_add_categoria->setAction(new TAction(['CategoriaForm', 'onEdit']), 'Nova Categoria');
        $btn_add_categoria->setImage('fa:plus green');
        $btn_add_categoria->class = 'btn btn-success btn-sm';
        
        $btn_add_produto = new TButton('btn_add_produto');
        $btn_add_produto->setAction(new TAction(['ProdutoForm', 'onEdit']), 'Novo Produto');
        $btn_add_produto->setImage('fa:plus green');
        $btn_add_produto->class = 'btn btn-success btn-sm';
        
        $btn_gerenciar = new TButton('btn_gerenciar');
        $btn_gerenciar->setAction(new TAction(['ProdutoList', 'onReload']), 'Gerenciar');
        $btn_gerenciar->setImage('fa:cog blue');
        $btn_gerenciar->class = 'btn btn-primary btn-sm';
        
        // Registrar os botões no formulário
        $this->form->setFields([$btn_add_categoria, $btn_add_produto, $btn_gerenciar]);
        
        $admin_panel->add($btn_add_categoria);
        $admin_panel->add('&nbsp;');
        $admin_panel->add($btn_add_produto);
        $admin_panel->add('&nbsp;');
        $admin_panel->add($btn_gerenciar);
        
        $container->add($header);
        $container->add($this->form);
        $this->form->add($admin_panel);
        
        // Container das categorias
        $categorias_container = new TElement('div');
        $categorias_container->class = 'categorias-container';
        
        try
        {
            TTransaction::open('cardapio');
            
            $categorias = Categoria::all();
            
            if ($categorias)
            {
                foreach ($categorias as $categoria)
                {
                    $categoria_section = new TElement('div');
                    $categoria_section->class = 'categoria-section';
                    
                    // Header da categoria
                    $categoria_header = new TElement('div');
                    $categoria_header->class = 'categoria-header';
                    
                    $categoria_title = new TElement('div');
                    $categoria_title->class = 'categoria-title';
                    $categoria_title->add('<h2>' . $categoria->nome . '</h2>');
                    if ($categoria->descricao) {
                        $categoria_title->add('<p>' . $categoria->descricao . '</p>');
                    }
                    
                    // Botões de ação da categoria
                    $categoria_actions = new TElement('div');
                    $categoria_actions->class = 'categoria-actions';
                    $categoria_actions->add('<button type="button" class="btn btn-xs btn-primary" onclick="__adianti_load_page(\'?class=CategoriaForm&method=onEdit&key=' . $categoria->id . '\');">');
                    $categoria_actions->add('<i class="fa fa-edit"></i> Editar');
                    $categoria_actions->add('</button>');
                    $categoria_actions->add('&nbsp;');
                    $categoria_actions->add('<button type="button" class="btn btn-xs btn-danger" onclick="if(confirm(\'Tem certeza que deseja excluir esta categoria?\')) __adianti_load_page(\'index.php?class=CategoriaList&method=Delete&key=' . $categoria->id . '\');">');
                    $categoria_actions->add('<i class="fa fa-trash"></i> Excluir');
                    $categoria_actions->add('</button>');
                    
                    $categoria_header->add($categoria_title);
                    $categoria_header->add($categoria_actions);
                    
                    $categoria_section->add($categoria_header);
                    
                    // Grid de produtos
                    $produtos_grid = new TElement('div');
                    $produtos_grid->class = 'produtos-grid';
                    
                    // Usar o método estático da classe Produto
                    $produtos = Produto::getByCategoria($categoria->id);
                    
                    if ($produtos)
                    {
                        foreach ($produtos as $produto)
                        {
                            $produto_card = new TElement('div');
                            $produto_card->class = 'produto-card';
                            
                            // Imagem do produto
                            $produto_imagem = new TElement('div');
                            $produto_imagem->class = 'produto-imagem';
                            
                            if (!empty($produto->imagem) && file_exists('uploads/produtos/' . $produto->imagem)) {
                                $img = new TElement('img');
                                $img->src = 'uploads/produtos/' . $produto->imagem;
                                $img->alt = $produto->nome;
                                $produto_imagem->add($img);
                            } else {
                                $placeholder = new TElement('div');
                                $placeholder->class = 'produto-placeholder';
                                $placeholder->add('<i class="fa fa-cutlery fa-3x"></i>');
                                $produto_imagem->add($placeholder);
                            }
                            
                            $produto_info = new TElement('div');
                            $produto_info->class = 'produto-info';
                            
                            $nome = new TElement('h3');
                            $nome->class = 'produto-nome';
                            $nome->add($produto->nome);
                            
                            $descricao = new TElement('p');
                            $descricao->class = 'produto-descricao';
                            $descricao->add($produto->descricao ?: 'Delicioso produto do nosso cardápio.');
                            
                            $preco = new TElement('div');
                            $preco->class = 'produto-preco';
                            $preco->add('R$ ' . number_format($produto->preco, 2, ',', '.'));
                            
                            // Botões de ação
                            $produto_actions = new TElement('div');
                            $produto_actions->class = 'produto-actions';
                            $produto_actions->add('<button type="button" class="btn btn-xs btn-primary" onclick="__adianti_load_page(\'?class=ProdutoForm&method=onEdit&key=' . $produto->id . '\');">');
                            $produto_actions->add('<i class="fa fa-edit"></i> Editar');
                            $produto_actions->add('</button>');
                            $produto_actions->add('&nbsp;');
                            $produto_actions->add('<button type="button" class="btn btn-xs btn-danger" onclick="if(confirm(\'Tem certeza que deseja excluir este produto?\')) __adianti_load_page(\'index.php?class=ProdutoList&method=Delete&key=' . $produto->id . '\');">');
                            $produto_actions->add('<i class="fa fa-trash"></i> Excluir');
                            $produto_actions->add('</button>');
                            
                            $produto_info->add($nome);
                            $produto_info->add($descricao);
                            $produto_info->add($preco);
                            $produto_info->add($produto_actions);
                            
                            $produto_card->add($produto_imagem);
                            $produto_card->add($produto_info);
                            $produtos_grid->add($produto_card);
                        }
                    }
                    else
                    {
                        $empty_msg = new TElement('div');
                        $empty_msg->class = 'empty-category';
                        $empty_msg->add('<p>Nenhum produto disponível nesta categoria.</p>');
                        $produtos_grid->add($empty_msg);
                    }
                    
                    $categoria_section->add($produtos_grid);
                    $categorias_container->add($categoria_section);
                }
            }
            else
            {
                $empty_cardapio = new TElement('div');
                $empty_cardapio->class = 'empty-cardapio';
                $empty_cardapio->add('<div class="text-center">');
                $empty_cardapio->add('<i class="fa fa-cutlery fa-5x text-muted"></i>');
                $empty_cardapio->add('<h3>Cardápio em construção</h3>');
                $empty_cardapio->add('<p>Em breve teremos deliciosos pratos para você!</p>');
                $empty_cardapio->add('</div>');
                $categorias_container->add($empty_cardapio);
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
        
        $container->add($categorias_container);
        
        // Footer
        $footer = new TElement('div');
        $footer->class = 'cardapio-footer';
        $footer->add('<p><i class="fa fa-heart text-danger"></i> Feito com carinho para você!</p>');
        $container->add($footer);
        
        parent::add($container);
    }
    
    public static function onReload()
    {
        $page = new self;
        $page->show();
    }
}

?>

