<?php
header('Content-Type: application/json');

// Verificar se o upload foi feito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    echo json_encode([
        'post_data' => $_POST,
        'files_data' => $_FILES,
        'upload_enabled' => ini_get('file_uploads'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'temp_dir' => sys_get_temp_dir(),
        'upload_tmp_dir' => ini_get('upload_tmp_dir'),
        'errors' => [
            'UPLOAD_ERR_OK' => UPLOAD_ERR_OK,
            'UPLOAD_ERR_INI_SIZE' => UPLOAD_ERR_INI_SIZE,
            'UPLOAD_ERR_FORM_SIZE' => UPLOAD_ERR_FORM_SIZE,
            'UPLOAD_ERR_PARTIAL' => UPLOAD_ERR_PARTIAL,
            'UPLOAD_ERR_NO_FILE' => UPLOAD_ERR_NO_FILE,
            'UPLOAD_ERR_NO_TMP_DIR' => UPLOAD_ERR_NO_TMP_DIR,
            'UPLOAD_ERR_CANT_WRITE' => UPLOAD_ERR_CANT_WRITE,
            'UPLOAD_ERR_EXTENSION' => UPLOAD_ERR_EXTENSION
        ]
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste de Upload</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Teste de Upload</h1>
    
    <form action="test_upload.php" method="post" enctype="multipart/form-data">
        <p>
            <label>Selecione uma imagem:</label><br>
            <input type="file" name="imagem" accept="image/*" required>
        </p>
        <p>
            <input type="submit" value="Enviar">
        </p>
    </form>
    
    <h2>Configurações PHP:</h2>
    <ul>
        <li>file_uploads: <?php echo ini_get('file_uploads') ? 'On' : 'Off'; ?></li>
        <li>upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></li>
        <li>post_max_size: <?php echo ini_get('post_max_size'); ?></li>
        <li>max_file_uploads: <?php echo ini_get('max_file_uploads'); ?></li>
        <li>upload_tmp_dir: <?php echo ini_get('upload_tmp_dir'); ?></li>
        <li>sys_get_temp_dir(): <?php echo sys_get_temp_dir(); ?></li>
    </ul>
    
    <h2>Permissões de Diretórios:</h2>
    <ul>
        <li>app/images/produtos existe: <?php echo is_dir('app/images/produtos') ? 'Sim' : 'Não'; ?></li>
        <li>app/images/produtos é gravável: <?php echo is_writable('app/images/produtos') ? 'Sim' : 'Não'; ?></li>
        <li>Diretório temp existe: <?php echo is_dir(ini_get('upload_tmp_dir')) ? 'Sim' : 'Não'; ?></li>
        <li>Diretório temp é gravável: <?php echo is_writable(ini_get('upload_tmp_dir')) ? 'Sim' : 'Não'; ?></li>
    </ul>
</body>
</html>

