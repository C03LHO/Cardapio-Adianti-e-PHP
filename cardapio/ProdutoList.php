<?php

class ProdutoList extends TPage
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;

    public function __construct()
    {
        parent::__construct();

        $this->form = new TQuickForm("form_search_produto");
        $this->form->class = 'tform';
        $this->form->setFormTitle("Produtos");

        $nome = new TEntry("nome");
        $categoria_id = new TDBCombo("categoria_id", "cardapio", "Categoria", "id", "nome");
        $status = new TCombo("status");
        $status->addItems(["ativo" => "Ativo", "inativo" => "Inativo"]);

        $this->form->addQuickField("Nome", $nome, 200);
        $this->form->addQuickField("Categoria", $categoria_id, 200);
        $this->form->addQuickField("Status", $status, 100);

        $this->form->addQuickAction("Buscar", new TAction(array($this, 'onSearch')), 'fa:search blue');
        $this->form->addQuickAction("Novo", new TAction(array('ProdutoForm', 'onClear')), 'fa:plus green');

        $this->datagrid = new TDataGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $col_id        = new TDataGridColumn('id', 'ID', 'center', 50);
        $col_nome      = new TDataGridColumn('nome', 'Nome', 'left', 200);
        $col_descricao = new TDataGridColumn('descricao', 'Descrição', 'left', 300);
        $col_preco     = new TDataGridColumn('preco', 'Preço', 'right', 100);
        $col_categoria = new TDataGridColumn('categoria->nome', 'Categoria', 'left', 150);
        $col_status    = new TDataGridColumn('status', 'Status', 'center', 80);

        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_nome);
        $this->datagrid->addColumn($col_descricao);
        $this->datagrid->addColumn($col_preco);
        $this->datagrid->addColumn($col_categoria);
        $this->datagrid->addColumn($col_status);

        $action_edit   = new TDataGridAction(array('ProdutoForm', 'onEdit'));
        $action_edit->setLabel('Editar');
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);

        $action_delete = new TDataGridAction(array($this, 'onDelete'));
        $action_delete->setLabel('Excluir');
        $action_delete->setImage('fa:trash-o red fa-lg');
        $action_delete->setField('id');
        $this->datagrid->addAction($action_delete);

        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $vbox = new TVBox;
        $vbox->add($this->form);
        $vbox->add($this->datagrid);
        $vbox->add($this->pageNavigation);

        parent::add($vbox);
    }

    public function onReload($param = NULL)
    {
        try
        {
            TTransaction::open('cardapio');
            $repository = new TRepository('Produto');
            $limit = 10;
            $criteria = new TCriteria;
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);
            $criteria->setProperty('order', 'nome');

            if (TSession::getValue('produto_filter'))
            {
                $criteria->add(TSession::getValue('produto_filter'));
            }

            $objects = $repository->load($criteria);
            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $this->datagrid->addItem($object);
                }
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onSearch()
    {
        $data = $this->form->getData();
        $criteria = new TCriteria;
        if (isset($data->nome) AND ($data->nome))
        {
            $criteria->add(new TFilter('nome', 'like', "%{$data->nome}%"));
        }
        if (isset($data->categoria_id) AND ($data->categoria_id))
        {
            $criteria->add(new TFilter('categoria_id', '=', $data->categoria_id));
        }
        if (isset($data->status) AND ($data->status))
        {
            $criteria->add(new TFilter('status', '=', $data->status));
        }
        TSession::setValue('produto_filter', $criteria);
        $this->form->setData($data);
        $this->onReload();
    }

    public function onDelete($param)
    {
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param);
        new TQuestion('Deseja realmente excluir o registro?', $action);
    }

    public function Delete($param)
    {
        try
        {
            $key = $param['key'];
            TTransaction::open('cardapio');
            $repository = new TRepository('Produto');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('id', '=', $key));
            $repository->delete($criteria);
            TTransaction::close();
            $this->onReload();
            new TMessage('info', 'Registro excluído com sucesso!');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function show()
    {
        $this->onReload();
        parent::show();
    }
}

?>

