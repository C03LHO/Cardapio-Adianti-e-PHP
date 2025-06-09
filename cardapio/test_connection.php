<?php
require_once 'init.php';

try {
    // Testar conexão
    TTransaction::open('cardapio');
    
    // Testar consulta nas categorias
    $categorias = new TRepository('Categoria');
    $result = $categorias->load();
    
    echo "Conexão com banco de dados: OK\n";
    echo "Categorias encontradas: " . count($result) . "\n";
    
    foreach ($result as $categoria) {
        echo "ID: {$categoria->id} - Nome: {$categoria->nome} - Status: {$categoria->status}\n";
    }
    
    TTransaction::close();
    
    echo "\nTeste concluído com sucesso!\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    TTransaction::rollback();
}
?>

