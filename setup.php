<?php
/**
 * Script de configuração inicial do Cardápio Digital
 * Este script cria o banco de dados e insere dados de exemplo
 */

// Incluir o framework Adianti
require_once 'init.php';

try {
    echo "<h1>Configuração do Cardápio Digital</h1>";
    echo "<p>Inicializando o banco de dados...</p>";
    
    // Conectar ao banco
    TTransaction::open('cardapio');
    
    // Ler e executar o script SQL
    $sql_file = 'database_structure.sql';
    
    if (file_exists($sql_file)) {
        $sql_content = file_get_contents($sql_file);
        
        // Dividir em comandos individuais
        $commands = explode(';', $sql_content);
        
        foreach ($commands as $command) {
            $command = trim($command);
            if (!empty($command) && !preg_match('/^(--|\s*$)/', $command)) {
                try {
                    TTransaction::get()->exec($command);
                    echo "<div style='color: green;'>✓ Comando executado com sucesso</div>";
                } catch (Exception $e) {
                    // Ignorar erros de 'já existe'
                    if (strpos($e->getMessage(), 'already exists') === false && 
                        strpos($e->getMessage(), 'Duplicate entry') === false) {
                        echo "<div style='color: orange;'>⚠ Aviso: " . $e->getMessage() . "</div>";
                    }
                }
            }
        }
    }
    
    TTransaction::close();
    
    echo "<div style='color: green; font-weight: bold; margin-top: 20px;'>";
    echo "✅ Configuração concluída com sucesso!";
    echo "</div>";
    
    echo "<div style='margin-top: 20px;'>";
    echo "<a href='?class=CardapioModerno' class='btn btn-primary'>Ver Cardápio</a> ";
    echo "<a href='?class=CategoriaForm' class='btn btn-success'>Cadastrar Categoria</a> ";
    echo "<a href='?class=ProdutoForm' class='btn btn-success'>Cadastrar Produto</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "❌ Erro na configuração: " . $e->getMessage();
    echo "</div>";
    
    echo "<div style='margin-top: 20px;'>";
    echo "<h3>Verifique:</h3>";
    echo "<ul>";
    echo "<li>Se o MySQL está rodando</li>";
    echo "<li>Se as configurações de banco em app/config/cardapio.ini estão corretas</li>";
    echo "<li>Se o banco 'cardapio' foi criado</li>";
    echo "</ul>";
    echo "</div>";
    
    TTransaction::rollback();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Setup - Cardápio Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .btn {
            margin: 5px;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            border: none;
        }
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Conteúdo gerado pelo PHP acima -->
    </div>
</body>
</html>

