<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="fas fa-edit"></i> Editar Veículo
                </h4>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="index.php?controller=veiculo&action=edit&id=<?php echo $this->veiculo->id; ?>" id="formVeiculo">
                    <input type="hidden" name="id" value="<?php echo $this->veiculo->id; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="placa" class="form-label">
                                <i class="fas fa-id-card"></i> Placa <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="placa" 
                                   name="placa" 
                                   value="<?php echo htmlspecialchars($this->veiculo->placa); ?>" 
                                   placeholder="AAA-9999"
                                   maxlength="8"
                                   required>
                            <small class="text-muted">Formato: AAA-9999</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label">
                                <i class="fas fa-car"></i> Modelo <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="modelo" 
                                   name="modelo" 
                                   value="<?php echo htmlspecialchars($this->veiculo->modelo); ?>" 
                                   placeholder="Ex: Civic, Corolla, Ônibus"
                                   required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label">
                                <i class="fas fa-trademark"></i> Marca <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="marca" 
                                   name="marca" 
                                   value="<?php echo htmlspecialchars($this->veiculo->marca); ?>" 
                                   placeholder="Ex: Honda, Toyota, Mercedes"
                                   required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="ano" class="form-label">
                                <i class="fas fa-calendar-alt"></i> Ano <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="ano" 
                                   name="ano" 
                                   value="<?php echo $this->veiculo->ano; ?>" 
                                   placeholder="AAAA"
                                   min="1900"
                                   max="<?php echo date('Y') + 1; ?>"
                                   required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="cor" class="form-label">
                                <i class="fas fa-palette"></i> Cor
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="cor" 
                                       name="cor" 
                                       value="<?php echo htmlspecialchars($this->veiculo->cor); ?>" 
                                       placeholder="Ex: Branco, Preto, Prata">
                                <input type="color" 
                                       class="form-control form-control-color" 
                                       id="corPicker" 
                                       value="#000000"
                                       style="width: 60px;">
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="km_atual" class="form-label">
                                <i class="fas fa-tachometer-alt"></i> KM Atual <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control" 
                                       id="km_atual" 
                                       name="km_atual" 
                                       value="<?php echo $this->veiculo->km_atual; ?>" 
                                       placeholder="0"
                                       min="0"
                                       required>
                                <span class="input-group-text">km</span>
                            </div>
                            <small class="text-muted">Quilometragem atual do veículo</small>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-circle"></i> Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="ativo" <?php echo $this->veiculo->status == 'ativo' ? 'selected' : ''; ?>>
                                    <i class="fas fa-check-circle"></i> Ativo
                                </option>
                                <option value="manutencao" <?php echo $this->veiculo->status == 'manutencao' ? 'selected' : ''; ?>>
                                    <i class="fas fa-tools"></i> Em Manutenção
                                </option>
                                <option value="inativo" <?php echo $this->veiculo->status == 'inativo' ? 'selected' : ''; ?>>
                                    <i class="fas fa-ban"></i> Inativo
                                </option>
                            </select>
                            <small class="text-muted">
                                <span class="badge bg-info">Atenção:</span> 
                                Veículos em uso não podem ter o status alterado manualmente
                            </small>
                        </div>
                    </div>
                    
                    <!-- Informações adicionais -->
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informações do Sistema:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>ID do Veículo:</strong> <?php echo $this->veiculo->id; ?></li>
                            <li><strong>Data de Cadastro:</strong> <?php echo date('d/m/Y H:i:s', strtotime($this->veiculo->created_at)); ?></li>
                            <li><strong>Última Atualização:</strong> <?php echo date('d/m/Y H:i:s', strtotime($this->veiculo->updated_at)); ?></li>
                        </ul>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-save"></i> Atualizar Veículo
                        </button>
                        <a href="index.php?controller=veiculo&action=show&id=<?php echo $this->veiculo->id; ?>" 
                           class="btn btn-info btn-lg">
                            <i class="fas fa-eye"></i> Visualizar
                        </a>
                        <a href="index.php?controller=veiculo&action=index" 
                           class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Card de histórico de alterações recentes -->
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history"></i> Últimas Alterações
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Campo</th>
                                <th>Valor Anterior</th>
                                <th>Novo Valor</th>
                                <th>Usuário</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-database"></i> Histórico disponível no módulo de relatórios
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Máscara para a placa
    $('#placa').mask('AAA-9999', {
        placeholder: '___-____',
        translation: {
            'A': { pattern: /[A-Za-z]/ },
            '9': { pattern: /[0-9]/ }
        }
    });
    
    // Sincronizar seletor de cor com o campo de texto
    $('#cor').on('input', function() {
        var cor = $(this).val();
        if(cor && cor.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/)) {
            $('#corPicker').val(cor);
        }
    });
    
    $('#corPicker').on('change', function() {
        $('#cor').val($(this).val());
    });
    
    // Validação do formulário antes de enviar
    $('#formVeiculo').on('submit', function(e) {
        var placa = $('#placa').val();
        var modelo = $('#modelo').val();
        var marca = $('#marca').val();
        var ano = $('#ano').val();
        var km_atual = $('#km_atual').val();
        var status = $('#status').val();
        
        // Validar placa
        if(!placa.match(/^[A-Za-z]{3}-\d{4}$/)) {
            alert('Por favor, informe uma placa válida no formato AAA-9999');
            $('#placa').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar modelo
        if(modelo.trim() === '') {
            alert('Por favor, informe o modelo do veículo');
            $('#modelo').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar marca
        if(marca.trim() === '') {
            alert('Por favor, informe a marca do veículo');
            $('#marca').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar ano
        var anoAtual = new Date().getFullYear();
        if(ano < 1900 || ano > anoAtual + 1) {
            alert('Por favor, informe um ano válido (1900 a ' + (anoAtual + 1) + ')');
            $('#ano').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar KM
        if(km_atual < 0) {
            alert('A quilometragem não pode ser negativa');
            $('#km_atual').focus();
            e.preventDefault();
            return false;
        }
        
        // Confirmar alteração de status
        var statusAtual = '<?php echo $this->veiculo->status; ?>';
        if(status !== statusAtual && status === 'manutencao') {
            if(!confirm('Ao alterar o status para "Em Manutenção", o veículo não poderá ser utilizado. Deseja continuar?')) {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });
    
    // Adicionar confirmação antes de sair sem salvar
    var formModified = false;
    $('#formVeiculo input, #formVeiculo select').on('change', function() {
        formModified = true;
    });
    
    window.onbeforeunload = function() {
        if(formModified) {
            return 'Você fez alterações não salvas. Deseja realmente sair?';
        }
    };
    
    $('#formVeiculo').on('submit', function() {
        formModified = false;
    });
    
    // Exibir preview da cor
    $('#cor').on('input', function() {
        var cor = $(this).val();
        if(cor) {
            // Se for nome de cor em inglês, tenta converter para hex
            var temp = $('<div style="color: ' + cor + '">').css('color');
            if(temp !== '') {
                $('#cor').css('border-left', '5px solid ' + cor);
            }
        } else {
            $('#cor').css('border-left', '');
        }
    }).trigger('input');
});
</script>

<style>
/* Estilos específicos para a página de edição */
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-label i {
    margin-right: 5px;
    color: #ffc107;
}

.card-header i {
    margin-right: 10px;
}

input:invalid, select:invalid {
    border-color: #dc3545;
}

input:valid, select:valid {
    border-color: #28a745;
}

#formVeiculo input:focus, #formVeiculo select:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    border-color: #ffc107;
}

.btn {
    margin: 0 5px;
}

.alert-info ul {
    list-style: none;
    padding-left: 0;
}

.alert-info ul li {
    padding: 5px 0;
}

/* Animação para o botão de submit */
.btn-warning {
    transition: all 0.3s ease;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Estilo para campos obrigatórios */
.text-danger {
    font-size: 1.2em;
}
</style>

<?php include 'views/layout/footer.php'; ?>