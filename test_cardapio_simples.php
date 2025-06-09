<?php
// Teste simples para verificar se o CardapioModerno está funcionando

require_once 'init.php';

try {
    echo "<h2>Testando métodos da classe Produto</h2>";
    
    TTransaction::open('cardapio');
    
    // Teste 1: Verificar se o método estático funciona
    echo "<p>Teste 1: Buscando produtos ativos...</p>";
    $produtos_ativos = Produto::getAtivos();
    echo "<p>Produtos ativos encontrados: " . (is_array($produtos_ativos) ? count($produtos_ativos) : 0) . "</p>";
    
    // Teste 2: Verificar se conseguimos buscar por categoria
    echo "<p>Teste 2: Buscando categorias...</p>";
    $categorias = Categoria::all();
    echo "<p>Categorias encontradas: " . (is_array($categorias) ? count($categorias) : 0) . "</p>";
    
    if ($categorias && count($categorias) > 0) {
        $primeira_categoria = $categorias[0];
        echo "<p>Teste 3: Buscando produtos da categoria '" . $primeira_categoria->nome . "'...</p>";
        $produtos_categoria = Produto::getByCategoria($primeira_categoria->id);
        echo "<p>Produtos na categoria encontrados: " . (is_array($produtos_categoria) ? count($produtos_categoria) : 0) . "</p>";
    }
    
    TTransaction::close();
    
    echo "<p style='color: green;'><strong>Todos os testes passaram! O CardapioModerno deve estar funcionando.</strong></p>";
    echo "<p><a href='?class=CardapioModerno'>Ir para o Cardápio Moderno</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Erro:</strong> " . $e->getMessage() . "</p>";
    TTransaction::rollback();
}
?>

