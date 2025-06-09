<?php
// Verificação de configurações PHP para upload

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Verificação de Configurações PHP</h1>";

// Função para converter bytes em formato legível
function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

// Função para converter valor do PHP em bytes
function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int) $value;
    switch($last) {
        case 'g': $value *= 1024;
        case 'm': $value *= 1024;
        case 'k': $value *= 1024;
    }
    return $value;
}

$config_checks = [
    'file_uploads' => [
        'value' => ini_get('file_uploads'),
        'expected' => true,
        'description' => 'Upload de arquivos habilitado'
    ],
    'upload_max_filesize' => [
        'value' => ini_get('upload_max_filesize'),
        'expected' => '2M',
        'description' => 'Tamanho máximo de arquivo para upload'
    ],
    'post_max_size' => [
        'value' => ini_get('post_max_size'),
        'expected' => '8M',
        'description' => 'Tamanho máximo de dados POST'
    ],
    'max_file_uploads' => [
        'value' => ini_get('max_file_uploads'),
        'expected' => 20,
        'description' => 'Número máximo de uploads simultâneos'
    ],
    'upload_tmp_dir' => [
        'value' => ini_get('upload_tmp_dir') ?: sys_get_temp_dir(),
        'expected' => null,
        'description' => 'Diretório temporário para uploads'
    ],
    'max_execution_time' => [
        'value' => ini_get('max_execution_time'),
        'expected' => 30,
        'description' => 'Tempo máximo de execução'
    ],
    'memory_limit' => [
        'value' => ini_get('memory_limit'),
        'expected' => '128M',
        'description' => 'Limite de memória'
    ]
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Configuração</th><th>Valor Atual</th><th>Recomendado</th><th>Status</th><th>Descrição</th>";
echo "</tr>";

foreach ($config_checks as $key => $check) {
    $value = $check['value'];
    $expected = $check['expected'];
    $description = $check['description'];
    
    // Determinar status
    $status = '✓';
    $color = 'green';
    
    if ($key === 'file_uploads' && !$value) {
        $status = '✗';
        $color = 'red';
    } elseif ($key === 'upload_max_filesize' || $key === 'post_max_size' || $key === 'memory_limit') {
        $valueBytes = convertToBytes($value);
        $expectedBytes = convertToBytes($expected);
        if ($valueBytes < $expectedBytes) {
            $status = '⚠';
            $color = 'orange';
        }
    } elseif ($key === 'max_file_uploads' && $value < $expected) {
        $status = '⚠';
        $color = 'orange';
    } elseif ($key === 'max_execution_time' && $value < $expected && $value != 0) {
        $status = '⚠';
        $color = 'orange';
    }
    
    echo "<tr>";
    echo "<td><strong>$key</strong></td>";
    echo "<td>$value</td>";
    echo "<td>" . ($expected ?: 'N/A') . "</td>";
    echo "<td style='color: $color; font-size: 18px;'>$status</td>";
    echo "<td>$description</td>";
    echo "</tr>";
}

echo "</table>";

// Verificar diretórios
echo "<h2>Verificação de Diretórios</h2>";

$directories = [
    'app/images/produtos' => 'Destino das imagens de produtos',
    'tmp' => 'Diretório temporário do projeto',
    sys_get_temp_dir() => 'Diretório temporário do sistema'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Diretório</th><th>Existe</th><th>Gravável</th><th>Permissões</th><th>Descrição</th>";
echo "</tr>";

foreach ($directories as $dir => $desc) {
    $exists = is_dir($dir);
    $writable = $exists ? is_writable($dir) : false;
    $perms = $exists ? substr(sprintf('%o', fileperms($dir)), -4) : 'N/A';
    
    $existsStatus = $exists ? '✓' : '✗';
    $writableStatus = $writable ? '✓' : '✗';
    
    $existsColor = $exists ? 'green' : 'red';
    $writableColor = $writable ? 'green' : 'red';
    
    echo "<tr>";
    echo "<td><strong>$dir</strong></td>";
    echo "<td style='color: $existsColor;'>$existsStatus</td>";
    echo "<td style='color: $writableColor;'>$writableStatus</td>";
    echo "<td>$perms</td>";
    echo "<td>$desc</td>";
    echo "</tr>";
}

echo "</table>";

// Testar criação de arquivo temporário
echo "<h2>Teste de Escrita</h2>";

$test_file = 'tmp/test_' . uniqid() . '.txt';
$test_content = 'Teste de escrita - ' . date('Y-m-d H:i:s');

if (file_put_contents($test_file, $test_content)) {
    echo "<p style='color: green;'>✓ Teste de escrita no diretório tmp: <strong>SUCESSO</strong></p>";
    if (file_exists($test_file)) {
        echo "<p>✓ Arquivo criado com sucesso: $test_file</p>";
        unlink($test_file);
        echo "<p>✓ Arquivo removido com sucesso</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Teste de escrita no diretório tmp: <strong>FALHOU</strong></p>";
}

// Verificar extensões PHP necessárias
echo "<h2>Extensões PHP</h2>";

$required_extensions = [
    'gd' => 'Manipulação de imagens',
    'fileinfo' => 'Detecção de tipo de arquivo',
    'pdo' => 'Acesso ao banco de dados',
    'pdo_mysql' => 'Conexão MySQL via PDO',
    'mysqli' => 'Conexão MySQL',
    'mbstring' => 'Manipulação de strings multibyte',
    'json' => 'Manipulação de JSON'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Extensão</th><th>Status</th><th>Descrição</th>";
echo "</tr>";

foreach ($required_extensions as $ext => $desc) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? '✓' : '✗';
    $color = $loaded ? 'green' : 'red';
    
    echo "<tr>";
    echo "<td><strong>$ext</strong></td>";
    echo "<td style='color: $color; font-size: 18px;'>$status</td>";
    echo "<td>$desc</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Resumo</h2>";
echo "<p>Esta página verifica as configurações necessárias para o funcionamento correto do upload de imagens.</p>";
echo "<p><strong>Legenda:</strong></p>";
echo "<ul>";
echo "<li style='color: green;'>✓ - OK</li>";
echo "<li style='color: orange;'>⚠ - Atenção (pode funcionar, mas não é ideal)</li>";
echo "<li style='color: red;'>✗ - Problema (precisa ser corrigido)</li>";
echo "</ul>";

?>

