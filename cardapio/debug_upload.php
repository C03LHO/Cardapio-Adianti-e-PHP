<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Debug de Upload - Cardápio</h1>";

// Configurações PHP relacionadas ao upload
echo "<h2>Configurações PHP:</h2>";
echo "<ul>";
echo "<li>file_uploads: " . (ini_get('file_uploads') ? 'On' : 'Off') . "</li>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "<li>max_file_uploads: " . ini_get('max_file_uploads') . "</li>";
echo "<li>upload_tmp_dir: " . (ini_get('upload_tmp_dir') ?: sys_get_temp_dir()) . "</li>";
echo "<li>max_execution_time: " . ini_get('max_execution_time') . "</li>";
echo "<li>max_input_time: " . ini_get('max_input_time') . "</li>";
echo "<li>memory_limit: " . ini_get('memory_limit') . "</li>";
echo "</ul>";

// Verificar diretórios
echo "<h2>Verificação de Diretórios:</h2>";
echo "<ul>";

$directories = [
    'app/images/produtos' => 'Diretório de destino das imagens',
    'tmp' => 'Diretório temporário do projeto',
    sys_get_temp_dir() => 'Diretório temporário do sistema'
];

foreach ($directories as $dir => $desc) {
    $exists = is_dir($dir);
    $writable = $exists ? is_writable($dir) : false;
    
    echo "<li><strong>$desc</strong> ($dir):";
    echo "<ul>";
    echo "<li>Existe: " . ($exists ? 'Sim' : 'Não') . "</li>";
    echo "<li>Gravável: " . ($writable ? 'Sim' : 'Não') . "</li>";
    if ($exists) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "<li>Permissões: $perms</li>";
    }
    echo "</ul>";
    echo "</li>";
}

echo "</ul>";

// Testar conexão com banco
echo "<h2>Teste de Conexão com Banco de Dados:</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=cardapio;charset=utf8mb4', 'root', 'mysql');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Conexão com banco estabelecida com sucesso!</p>";
    
    // Verificar estrutura da tabela produtos
    $stmt = $pdo->query("DESCRIBE produtos");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Estrutura da tabela 'produtos':</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Contar registros
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
    $total = $stmt->fetch()['total'];
    echo "<p>Total de produtos: <strong>$total</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Erro na conexão: " . $e->getMessage() . "</p>";
}

// Formulário de teste
echo "<h2>Teste de Upload:</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['teste_imagem'])) {
    echo "<h3>Resultado do Upload:</h3>";
    
    $file = $_FILES['teste_imagem'];
    echo "<pre>";
    echo "Dados do arquivo:\n";
    print_r($file);
    echo "</pre>";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $target_dir = 'app/images/produtos/';
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'teste_' . date('Y-m-d_H-i-s') . '.' . $extension;
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            echo "<p style='color: green;'>✓ Upload realizado com sucesso!</p>";
            echo "<p>Arquivo salvo em: <strong>$target_file</strong></p>";
            echo "<p>Tamanho: " . number_format($file['size'] / 1024, 2) . " KB</p>";
            
            if (file_exists($target_file)) {
                echo "<p>✓ Arquivo confirmado no destino</p>";
                echo "<img src='$target_file' style='max-width: 200px; max-height: 200px;' alt='Imagem enviada'>";
            }
        } else {
            echo "<p style='color: red;'>✗ Erro ao mover arquivo para destino</p>";
        }
    } else {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Arquivo maior que upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'Arquivo maior que MAX_FILE_SIZE do formulário',
            UPLOAD_ERR_PARTIAL => 'Upload foi feito parcialmente',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado',
            UPLOAD_ERR_NO_TMP_DIR => 'Diretório temporário não encontrado',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever arquivo no disco',
            UPLOAD_ERR_EXTENSION => 'Upload parado por extensão PHP'
        ];
        
        $error_msg = $errors[$file['error']] ?? 'Erro desconhecido';
        echo "<p style='color: red;'>✗ Erro no upload: $error_msg (Código: {$file['error']})</p>";
    }
}

echo "
<form method='post' enctype='multipart/form-data'>
    <h3>Enviar arquivo de teste:</h3>
    <p>
        <input type='file' name='teste_imagem' accept='image/*' required>
        <input type='submit' value='Testar Upload'>
    </p>
</form>
";

echo "<h2>Arquivos existentes:</h2>";
$files = glob('app/images/produtos/*');
if (empty($files)) {
    echo "<p>Nenhum arquivo encontrado no diretório de produtos.</p>";
} else {
    echo "<ul>";
    foreach ($files as $file) {
        if (is_file($file)) {
            $size = number_format(filesize($file) / 1024, 2);
            echo "<li>$file ($size KB)</li>";
        }
    }
    echo "</ul>";
}

?>

