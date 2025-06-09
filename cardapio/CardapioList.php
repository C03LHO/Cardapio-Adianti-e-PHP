<?php

class CardapioList extends TPage
{
    public function __construct()
    {
        parent::__construct();

        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        parent::add($vbox);

        try
        {
            TTransaction::open('cardapio');

            $categorias = Categoria::all();

            if ($categorias)
            {
                foreach ($categorias as $categoria)
                {
                    $panel = new TPanelGroup($categoria->nome);
                    $panel->style = 'width: 100%';
                    $panel->add(new TLabel($categoria->descricao));

                    $datagrid = new TDataGrid;
                    $datagrid->style = 'width: 100%';
                    $datagrid->setHeight(150);

                    $col_nome      = new TDataGridColumn('nome', 'Produto', 'left', 200);
                    $col_descricao = new TDataGridColumn('descricao', 'Descrição', 'left', 400);
                    $col_preco     = new TDataGridColumn('preco', 'Preço', 'right', 100);

                    $datagrid->addColumn($col_nome);
                    $datagrid->addColumn($col_descricao);
                    $datagrid->addColumn($col_preco);

                    $datagrid->createModel();

                    $criteria = new TCriteria;
                    $criteria->add(new TFilter('categoria_id', '=', $categoria->id));
                    $criteria->add(new TFilter('status', '=', 'ativo'));
                    $produtos = Produto::getObjectsByCriteria($criteria);

                    if ($produtos)
                    {
                        foreach ($produtos as $produto)
                        {
                            $datagrid->addItem($produto);
                        }
                    }
                    $panel->add($datagrid);
                    $vbox->add($panel);
                }
            }
            else
            {
                $panel = new TPanelGroup('Cardápio Vazio');
                $panel->add(new TLabel('Nenhuma categoria ou produto encontrado.'));
                $vbox->add($panel);
            }

            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}

?>

