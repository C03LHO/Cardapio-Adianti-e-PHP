<?php

class ProdutoForm extends TPage
{
    protected $form;

    public function __construct()
    {
        parent::__construct();
        
        // Adicionar CSS personalizado
        TPage::include_css('app/resources/produto-form.css');

        $this->form = new BootstrapFormBuilder("form_produto");
        $this->form->setFormTitle('<i class="fa fa-cutlery"></i> Cadastro de Produto');
        $this->form->setColumnClasses(2, ['col-sm-3', 'col-sm-9']);

        $id          = new TEntry("id");
        $nome        = new TEntry("nome");
        $descricao   = new TText("descricao");
        $preco       = new TNumeric("preco", 2, ',', '.', true);
        $categoria_id = new TDBCombo("categoria_id", "cardapio", "Categoria", "id", "nome");
        $imagem      = new TFile("imagem");
        $status      = new TRadioGroup("status");

        // Configurações dos campos
        $id->setEditable(FALSE);
        $id->setSize('100%');
        
        $nome->setSize('100%');
        $nome->placeholder = 'Digite o nome do produto';
        $nome->setTip('Nome que aparecerá no cardápio');
        
        $descricao->setSize('100%', 100);
        $descricao->placeholder = 'Descreva os ingredientes e características do produto';
        
        $preco->setSize('100%');
        $preco->placeholder = '0,00';
        $preco->setTip('Preço em reais (R$)');
        
        $categoria_id->setSize('100%');
        $categoria_id->enableSearch();
        $categoria_id->setTip('Selecione a categoria do produto');
        
        $imagem->setSize('100%');
        $imagem->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
        $imagem->setTip('Imagem do produto (JPG, PNG ou GIF - máx. 2MB)');
        $imagem->enableFileHandling();
        $imagem->allowedExtensions = 'jpg,jpeg,png,gif'; // Configuração adicional
        
        $status->setLayout('horizontal');
        $status->addItems([
            'ativo' => '<i class="fa fa-check-circle text-success"></i> Ativo', 
            'inativo' => '<i class="fa fa-times-circle text-danger"></i> Inativo'
        ]);
        $status->setValue('ativo');

        // Adicionar os campos ao formulário
        $this->form->addFields(
            [new TLabel('ID:')], [$id]
        );
        
        $this->form->addFields(
            [new TLabel('Nome:', 'red')], [$nome]
        );
        
        $this->form->addFields(
            [new TLabel('Categoria:', 'red')], [$categoria_id]
        );
        
        $this->form->addFields(
            [new TLabel('Preço (R$):', 'red')], [$preco]
        );
        
        $this->form->addFields(
            [new TLabel('Descrição:')], [$descricao]
        );
        
        $this->form->addFields(
            [new TLabel('Imagem:')], [$imagem]
        );
        
        $this->form->addFields(
            [new TLabel('Status:', 'red')], [$status]
        );

        // Validações
        $nome->addValidation('Nome', new TRequiredValidator);
        $preco->addValidation('Preço', new TRequiredValidator);
        $categoria_id->addValidation('Categoria', new TRequiredValidator);
        $status->addValidation('Status', new TRequiredValidator);

        // Botões de ação
        $btn_save = $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');
        $btn_save->class = 'btn btn-success';
        
        $btn_clear = $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser');
        $btn_clear->class = 'btn btn-warning';
        
        $btn_list = $this->form->addAction('Voltar ao Cardápio', new TAction(['CardapioModerno', 'onReload']), 'fa:arrow-left');
        $btn_list->class = 'btn btn-info';
        
        $btn_manage = $this->form->addAction('Gerenciar Produtos', new TAction(['ProdutoList', 'onReload']), 'fa:list');
        $btn_manage->class = 'btn btn-primary';

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);
    }

    public function onSave()
    {
        try
        {
            TTransaction::open('cardapio');
            
            $this->form->validate();
            $data = $this->form->getData();
            
            $object = new Produto();
            
            // Se está editando, carrega o objeto existente
            if (!empty($data->id)) {
                $object = new Produto($data->id);
            }
            
            $object->nome = $data->nome;
            $object->descricao = $data->descricao;
            $object->preco = $data->preco;
            $object->categoria_id = $data->categoria_id;
            $object->status = $data->status;
            
            // Upload da imagem
            if (!empty($data->imagem)) {
                $target_folder = 'uploads/produtos/';
                
                // Criar diretório se não existir
                if (!file_exists($target_folder)) {
                    mkdir($target_folder, 0777, true);
                }
                
                // Decodificar o nome do arquivo se estiver URL-encoded
                $decoded_filename = urldecode($data->imagem);
                
                // Verificar se é um JSON (problema atual)
                if (strpos($decoded_filename, '{') === 0) {
                    $json_data = json_decode($decoded_filename, true);
                    if (isset($json_data['newFile'])) {
                        $decoded_filename = $json_data['newFile'];
                    }
                }
                
                // O Adianti já processou o upload, só precisamos mover o arquivo
                $source_file = $decoded_filename;
                
                if (file_exists($source_file)) {
                    $extension = strtolower(pathinfo($decoded_filename, PATHINFO_EXTENSION));
                    
                    // Debug: verificar se a extensão foi extraída corretamente
                    if (empty($extension)) {
                        // Tentar extrair do nome original se o decodificado não funcionar
                        $extension = strtolower(pathinfo($data->imagem, PATHINFO_EXTENSION));
                    }
                    
                    $filename = 'produto_' . uniqid() . '.' . $extension;
                    $target_file = $target_folder . $filename;
                    
                    // Validar tamanho do arquivo (2MB máximo)
                    if (filesize($source_file) > 2097152) {
                        throw new Exception('Arquivo muito grande. Tamanho máximo: 2MB');
                    }
                    
                    // Validar tipo de arquivo
                    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    // Verificação adicional usando finfo para detectar o tipo real do arquivo
                    if (function_exists('finfo_open')) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime_type = finfo_file($finfo, $source_file);
                        finfo_close($finfo);
                        
                        $allowed_mime_types = [
                            'image/jpeg',
                            'image/jpg', 
                            'image/png',
                            'image/gif'
                        ];
                        
                        if (!in_array($mime_type, $allowed_mime_types)) {
                            throw new Exception('Tipo de arquivo não permitido. Use: JPG, PNG ou GIF. Tipo detectado: ' . $mime_type);
                        }
                    }
                    
                    if (!in_array($extension, $allowed_types)) {
                        throw new Exception('Tipo de arquivo não permitido. Use: JPG, PNG ou GIF. Extensão detectada: ' . $extension);
                    }
                    
                    if (copy($source_file, $target_file)) {
                        $object->imagem = $filename;
                        unlink($source_file); // Remove arquivo temporário
                    } else {
                        throw new Exception('Erro ao mover arquivo da pasta temporária');
                    }
                } else {
                    throw new Exception('Arquivo temporário não encontrado: ' . $source_file);
                }
            } elseif (!empty($_FILES['imagem']['name'])) {
                // Fallback para upload direto (caso o Adianti não processe)
                $target_folder = 'uploads/produtos/';
                
                if (!file_exists($target_folder)) {
                    mkdir($target_folder, 0777, true);
                }
                
                $file = $_FILES['imagem'];
                
                // Verificar erros no upload
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $upload_errors = [
                        UPLOAD_ERR_INI_SIZE => 'Arquivo maior que upload_max_filesize',
                        UPLOAD_ERR_FORM_SIZE => 'Arquivo maior que MAX_FILE_SIZE',
                        UPLOAD_ERR_PARTIAL => 'Upload foi feito parcialmente',
                        UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado',
                        UPLOAD_ERR_NO_TMP_DIR => 'Diretório temporário não encontrado',
                        UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever no disco',
                        UPLOAD_ERR_EXTENSION => 'Upload parado por extensão'
                    ];
                    
                    $error_msg = $upload_errors[$file['error']] ?? 'Erro desconhecido no upload';
                    throw new Exception($error_msg);
                }
                
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $filename = 'produto_' . uniqid() . '.' . $extension;
                $target_file = $target_folder . $filename;
                
                // Validar tamanho
                if ($file['size'] > 2097152) {
                    throw new Exception('Arquivo muito grande. Tamanho máximo: 2MB');
                }
                
                // Validar tipo
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($extension, $allowed_types)) {
                    throw new Exception('Tipo de arquivo não permitido. Use: JPG, PNG ou GIF');
                }
                
                if (move_uploaded_file($file['tmp_name'], $target_file)) {
                    $object->imagem = $filename;
                } else {
                    throw new Exception('Erro ao fazer upload da imagem');
                }
            }
            
            $object->store();
            TTransaction::close();
            
            $this->form->setData($object);
            
            new TMessage('success', 'Produto salvo com sucesso!', new TAction(['CardapioModerno', 'onReload']));
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];
                TTransaction::open('cardapio');
                $object = new Produto($key);
                $this->form->setData($object);
                TTransaction::close();
            }
            else
            {
                $this->onClear();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onClear()
    {
        $this->form->clear(true);
        
        // Definir valores padrão
        $obj = new stdClass;
        $obj->status = 'ativo';
        $this->form->setData($obj);
    }
    
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}

?>
