<?php

class Produto extends TRecord
{
    const TABLENAME  = 'produtos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'max'; // {max, serial}

    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('preco');
        parent::addAttribute('categoria_id');
        parent::addAttribute('imagem');
        parent::addAttribute('status');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    /**
     * Method get_categoria
     */
    public function get_categoria()
    {
        return new Categoria($this->categoria_id);
    }

    /**
     * Método para buscar produtos por categoria e status
     */
    public static function getByCategoria($categoria_id, $status = 'ativo')
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('categoria_id', '=', $categoria_id));
        $criteria->add(new TFilter('status', '=', $status));
        $criteria->setProperty('order', 'nome');
        
        $repository = new TRepository('Produto');
        return $repository->load($criteria);
    }

    /**
     * Método para buscar produtos ativos
     */
    public static function getAtivos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('status', '=', 'ativo'));
        $criteria->setProperty('order', 'nome');
        
        $repository = new TRepository('Produto');
        return $repository->load($criteria);
    }
}

?>

