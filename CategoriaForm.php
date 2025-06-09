<?php

class CategoriaForm extends TPage
{
    protected $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new TQuickForm("form_categoria");
        $this->form->class = 'tform';
        $this->form->setFormTitle("Cadastro de Categoria");

        $id          = new TEntry("id");
        $nome        = new TEntry("nome");
        $descricao   = new TText("descricao");

        $id->setEditable(FALSE);
        $nome->setSize(300);
        $descricao->setSize(300, 80);

        $this->form->addQuickField("ID", $id, 50);
        $this->form->addQuickField("Nome", $nome, 300, new TRequiredValidator);
        $this->form->addQuickField("Descrição", $descricao, 300, new TRequiredValidator);

        $this->form->addQuickAction("Salvar", new TAction(array($this, 'onSave')), 'fa:save green');
        $this->form->addQuickAction("Limpar", new TAction(array($this, 'onClear')), 'fa:eraser red');
        $this->form->addQuickAction("Listar", new TAction(array('CategoriaList', 'onReload')), 'fa:list blue');

        $vbox = new TVBox;
        $vbox->add($this->form);

        parent::add($vbox);
    }

    public function onSave()
    {
        try
        {
            TTransaction::open('cardapio');
            $data = $this->form->getData();
            $object = new Categoria();
            $object->fromArray( (array) $data);
            $object->store();
            TTransaction::close();

            new TMessage('info', 'Registro salvo com sucesso!');
            $this->onClear();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];
                TTransaction::open('cardapio');
                $object = new Categoria($key);
                $this->form->setData($object);
                TTransaction::close();
            }
            else
            {
                $this->onClear();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onClear()
    {
        $this->form->clear();
    }
}

?>

