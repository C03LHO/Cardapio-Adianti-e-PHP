<?php

class Categoria extends TRecord
{
    const TABLENAME  = 'categorias';
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
        parent::addAttribute('status');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }
}

?>

