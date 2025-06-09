<?php
/**
 * Script de teste para verificar se o cardápio está funcionando
 */

// Incluir o framework Adianti
require_once 'init.php';

$tests = [];
$errors = [];

// Teste 1: Verificar conexão com banco
try {
    TTransaction::open('cardapio');
    $tests['database'] = '✅ Conexão com banco de dados: OK';
    TTransaction::close();
} catch (Exception $e) {
    $tests['database'] = '❌ Erro na conexão: ' . $e->getMessage();
    $errors[] = 'database';
}

// Teste 2: Verificar se as tabelas existem
try {
    TTransaction::open('cardapio');
    
    // Testar categoria
    $conn = TTransaction::get();
    $result = $conn->query("SHOW TABLES LIKE 'categorias'");
    if ($result->rowCount() > 0) {
        $tests['table_categorias'] = '✅ Tabela categorias: OK';
    } else {
        $tests['table_categorias'] = '❌ Tabela categorias não encontrada';
        $errors[] = 'table_categorias';
    }
    
    // Testar produtos
    $result = $conn->query("SHOW TABLES LIKE 'produtos'");
    if ($result->rowCount() > 0) {
        $tests['table_produtos'] = '✅ Tabela produtos: OK';
    } else {
        $tests['table_produtos'] = '❌ Tabela produtos não encontrada';
        $errors[] = 'table_produtos';
    }
    
    TTransaction::close();
} catch (Exception $e) {
    $tests['tables'] = '❌ Erro ao verificar tabelas: ' . $e->getMessage();
    $errors[] = 'tables';
}

// Teste 3: Verificar se existem dados
try {
    TTransaction::open('cardapio');
    
    $categorias = Categoria::all();
    $count_cat = $categorias ? count($categorias) : 0;
    $tests['data_categorias'] = "✅ Categorias encontradas: {$count_cat}";
    
    $produtos = Produto::all();
    $count_prod = $produtos ? count($produtos) : 0;
    $tests['data_produtos'] = "✅ Produtos encontrados: {$count_prod}";
    
    TTransaction::close();
} catch (Exception $e) {
    $tests['data'] = '❌ Erro ao verificar dados: ' . $e->getMessage();
    $errors[] = 'data';
}

// Teste 4: Verificar arquivos CSS e JS
$css_file = 'app/resources/cardapio-moderno.css';
if (file_exists($css_file)) {
    $tests['css'] = '✅ Arquivo CSS: OK';
} else {
    $tests['css'] = '❌ Arquivo CSS não encontrado';
    $errors[] = 'css';
}

$js_file = 'app/resources/cardapio-moderno.js';
if (file_exists($js_file)) {
    $tests['js'] = '✅ Arquivo JS: OK';
} else {
    $tests['js'] = '❌ Arquivo JS não encontrado';
    $errors[] = 'js';
}

// Teste 5: Verificar diretório de imagens
$img_dir = 'app/images/produtos';
if (is_dir($img_dir)) {
    $tests['images'] = '✅ Diretório de imagens: OK';
} else {
    $tests['images'] = '❌ Diretório de imagens não encontrado';
    $errors[] = 'images';
}

// Verificar se as classes existem
if (class_exists('CardapioModerno')) {
    $tests['class_cardapio'] = '✅ Classe CardapioModerno: OK';
} else {
    $tests['class_cardapio'] = '❌ Classe CardapioModerno não encontrada';
    $errors[] = 'class_cardapio';
}

if (class_exists('Categoria')) {
    $tests['class_categoria'] = '✅ Classe Categoria: OK';
} else {
    $tests['class_categoria'] = '❌ Classe Categoria não encontrada';
    $errors[] = 'class_categoria';
}

if (class_exists('Produto')) {
    $tests['class_produto'] = '✅ Classe Produto: OK';
} else {
    $tests['class_produto'] = '❌ Classe Produto não encontrada';
    $errors[] = 'class_produto';
}

// Exibir resultados
echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<meta charset='utf-8'>";
echo "<title>Teste do Sistema - Cardápio Digital</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
echo "<style>";
echo "body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }";
echo ".container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 8px 30px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }";
echo ".test-item { padding: 10px; margin: 5px 0; border-radius: 5px; }";
echo ".success { background: #d4edda; border-left: 4px solid #28a745; }";
echo ".error { background: #f8d7da; border-left: 4px solid #dc3545; }";
echo ".btn { margin: 5px; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; }";
echo ".btn-primary { background: linear-gradient(45deg, #007bff, #6610f2); color: white; border: none; }";
echo ".btn-success { background: linear-gradient(45deg, #28a745, #20c997); color: white; border: none; }";
echo ".btn-warning { background: linear-gradient(45deg, #ffc107, #fd7e14); color: white; border: none; }";
echo "h1 { color: #333; text-align: center; margin-bottom: 30px; }";
echo "</style>";
echo "</head><body>";
echo "<div class='container'>";

echo "<h1><i class='fa fa-flask'></i> Teste do Sistema</h1>";

echo "<div class='row'>";
echo "<div class='col-12'>";

foreach ($tests as $key => $result) {
    $isError = in_array($key, $errors);
    $class = $isError ? 'error' : 'success';
    echo "<div class='test-item {$class}'>{$result}</div>";
}

echo "</div>";
echo "</div>";

if (count($errors) > 0) {
    echo "<div class='alert alert-warning mt-4'>";
    echo "<h4><i class='fa fa-exclamation-triangle'></i> Problemas Encontrados</h4>";
    echo "<p>Alguns testes falharam. Execute o script de configuração para corrigir:</p>";
    echo "<a href='setup.php' class='btn btn-warning'>Executar Setup</a>";
    echo "</div>";
} else {
    echo "<div class='alert alert-success mt-4'>";
    echo "<h4><i class='fa fa-check'></i> Todos os Testes Passaram!</h4>";
    echo "<p>O sistema está funcionando corretamente.</p>";
    echo "</div>";
}

echo "<div class='mt-4'>";
echo "<h4>Navegação</h4>";
echo "<a href='?class=CardapioModerno' class='btn btn-primary'><i class='fa fa-cutlery'></i> Ver Cardápio</a> ";
echo "<a href='?class=CategoriaList' class='btn btn-success'><i class='fa fa-list'></i> Gerenciar Categorias</a> ";
echo "<a href='?class=ProdutoList' class='btn btn-success'><i class='fa fa-shopping-bag'></i> Gerenciar Produtos</a> ";
echo "<a href='test_cardapio.php' class='btn btn-warning'><i class='fa fa-refresh'></i> Executar Teste Novamente</a>";
echo "</div>";

echo "</div>";
echo "</body></html>";
?>

