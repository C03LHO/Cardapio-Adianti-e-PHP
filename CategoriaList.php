<?php

class CategoriaList extends TPage
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;

    public function __construct()
    {
        parent::__construct();

        $this->form = new TQuickForm("form_search_categoria");
        $this->form->class = 'tform';
        $this->form->setFormTitle("Categorias");

        $nome = new TEntry("nome");
        $this->form->addQuickField("Nome", $nome, 200);

        $this->form->addQuickAction("Buscar", new TAction(array($this, 'onSearch')), 'fa:search blue');
        $this->form->addQuickAction("Novo", new TAction(array('CategoriaForm', 'onClear')), 'fa:plus green');

        $this->datagrid = new TDataGrid;
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $col_id        = new TDataGridColumn('id', 'ID', 'center', 50);
        $col_nome      = new TDataGridColumn('nome', 'Nome', 'left', 200);
        $col_descricao = new TDataGridColumn('descricao', 'Descrição', 'left', 400);

        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_nome);
        $this->datagrid->addColumn($col_descricao);

        $action_edit   = new TDataGridAction(array('CategoriaForm', 'onEdit'));
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
            $repository = new TRepository('Categoria');
            $limit = 10;
            $criteria = new TCriteria;
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);
            $criteria->setProperty('order', 'nome');

            if (TSession::getValue('categoria_filter'))
            {
                $criteria->add(TSession::getValue('categoria_filter'));
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
        TSession::setValue('categoria_filter', $criteria);
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
            $repository = new TRepository('Categoria');
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

