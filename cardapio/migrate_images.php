<?php
/**
 * Script para migrar imagens da pasta tmp para uploads/produtos
 * Execute este arquivo uma vez para mover as imagens existentes
 */

echo "<h2>Migração de Imagens</h2>";
echo "<p>Movendo imagens da pasta 'tmp' para 'uploads/produtos'...</p>";

// Criar pasta de destino se não existir
if (!file_exists('uploads/produtos')) {
    mkdir('uploads/produtos', 0777, true);
    echo "<p style='color: green;'>✓ Pasta 'uploads/produtos' criada</p>";
}

// Listar arquivos na pasta tmp
$tmp_files = glob('tmp/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

if (empty($tmp_files)) {
    echo "<p style='color: orange;'>⚠ Nenhuma imagem encontrada na pasta 'tmp'</p>";
} else {
    echo "<p>Encontradas " . count($tmp_files) . " imagens:</p>";
    
    foreach ($tmp_files as $tmp_file) {
        $filename = basename($tmp_file);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Gerar novo nome único
        $new_filename = 'produto_' . uniqid() . '.' . $extension;
        $new_path = 'uploads/produtos/' . $new_filename;
        
        // Copiar arquivo
        if (copy($tmp_file, $new_path)) {
            echo "<p style='color: green;'>✓ Movido: {$filename} → {$new_filename}</p>";
            
            // Opcional: remover arquivo original
            // unlink($tmp_file);
        } else {
            echo "<p style='color: red;'>✗ Erro ao mover: {$filename}</p>";
        }
    }
}

echo "<hr>";
echo "<h3>Próximos passos:</h3>";
echo "<ol>";
echo "<li>As imagens foram copiadas para 'uploads/produtos'</li>";
echo "<li>Você pode editar os produtos existentes para selecionar as novas imagens</li>";
echo "<li>As imagens antigas na pasta 'tmp' podem ser removidas manualmente</li>";
echo "</ol>";

echo "<p><a href='?class=CardapioModerno' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Voltar ao Cardápio</a></p>";

?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #f8f9fa;
}
h2 {
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}
p {
    margin: 10px 0;
    padding: 5px;
}
hr {
    margin: 30px 0;
    border: none;
    border-top: 1px solid #ddd;
}
</style>

