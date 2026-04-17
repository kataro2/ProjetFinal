<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Nova Manutenção
                </h4>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="index.php?controller=manutencao&action=create" id="formManutencao">
                    <div class="row">
                        <!-- Veículo -->
                        <div class="col-md-12 mb-3">
                            <label for="veiculo_id" class="form-label">
                                <i class="fas fa-car"></i> Veículo <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="veiculo_id" name="veiculo_id" required>
                                <option value="">Selecione um veículo</option>
                                <?php if(isset($veiculos) && count($veiculos) > 0): ?>
                                    <?php foreach($veiculos as $veiculo): ?>
                                        <option value="<?php echo $veiculo['id']; ?>"
                                                data-placa="<?php echo $veiculo['placa']; ?>"
                                                data-modelo="<?php echo $veiculo['modelo']; ?>"
                                                data-km="<?php echo $veiculo['km_atual']; ?>">
                                            <?php echo $veiculo['placa'] . ' - ' . $veiculo['modelo'] . ' (' . $veiculo['marca'] . ')'; ?>
                                            <?php if($veiculo['status'] != 'ativo'): ?>
                                                - <span class="text-warning"><?php echo ucfirst($veiculo['status']); ?></span>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Nenhum veículo cadastrado</option>
                                <?php endif; ?>
                            </select>
                            <div id="veiculoInfo" class="mt-2" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Veículo selecionado:</strong>
                                    <span id="veiculoSelecionado"></span>
                                    <br>
                                    <strong>KM Atual:</strong>
                                    <span id="kmAtualVeiculo"></span> km
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data da Manutenção -->
                        <div class="col-md-6 mb-3">
                            <label for="data_manutencao" class="form-label">
                                <i class="fas fa-calendar-alt"></i> Data da Manutenção <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="data_manutencao" 
                                   name="data_manutencao" 
                                   value="<?php echo date('Y-m-d'); ?>"
                                   required>
                        </div>
                        
                        <!-- Tipo de Manutenção -->
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">
                                <i class="fas fa-tag"></i> Tipo de Manutenção <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="">Selecione o tipo</option>
                                <option value="preventiva">🔧 Preventiva</option>
                                <option value="corretiva">⚠️ Corretiva</option>
                                <option value="revisao">📋 Revisão Periódica</option>
                                <option value="troca_oleo">🛢️ Troca de Óleo</option>
                                <option value="pneus">🚗 Troca de Pneus</option>
                                <option value="suspensao">🔧 Suspensão</option>
                                <option value="freios">🛑 Freios</option>
                                <option value="eletrica">⚡ Elétrica</option>
                                <option value="motor">🔧 Motor</option>
                                <option value="outros">📌 Outros</option>
                            </select>
                        </div>
                        
                        <!-- Descrição -->
                        <div class="col-md-12 mb-3">
                            <label for="descricao" class="form-label">
                                <i class="fas fa-align-left"></i> Descrição <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" 
                                      id="descricao" 
                                      name="descricao" 
                                      rows="4" 
                                      placeholder="Descreva detalhadamente os serviços realizados ou a serem realizados..."
                                      required></textarea>
                            <small class="text-muted">Máximo 500 caracteres</small>
                            <div id="contadorCaracteres" class="text-end small"></div>
                        </div>
                        
                        <!-- Custo -->
                        <div class="col-md-6 mb-3">
                            <label for="custo" class="form-label">
                                <i class="fas fa-dollar-sign"></i> Custo (R$)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" 
                                       class="form-control" 
                                       id="custo" 
                                       name="custo" 
                                       placeholder="0,00"
                                       value="0,00">
                            </div>
                            <small class="text-muted">Deixe em branco se o custo não for conhecido</small>
                        </div>
                        
                        <!-- KM Atual -->
                        <div class="col-md-6 mb-3">
                            <label for="km_atual" class="form-label">
                                <i class="fas fa-tachometer-alt"></i> KM Atual <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="km_atual" 
                                   name="km_atual" 
                                   placeholder="0"
                                   min="0"
                                   required>
                            <small class="text-muted">Quilometragem do veículo no momento da manutenção</small>
                        </div>
                        
                        <!-- Próxima Manutenção -->
                        <div class="col-md-6 mb-3">
                            <label for="proxima_manutencao" class="form-label">
                                <i class="fas fa-calendar-check"></i> Próxima Manutenção
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="proxima_manutencao" 
                                   name="proxima_manutencao">
                            <small class="text-muted">Data sugerida para a próxima manutenção preventiva</small>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-circle"></i> Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="agendada" selected>📅 Agendada</option>
                                <option value="realizada">✅ Realizada</option>
                                <option value="cancelada">❌ Cancelada</option>
                            </select>
                            <small class="text-muted">
                                <span class="badge bg-info">Atenção:</span> 
                                Manutenções "Realizadas" alteram o status do veículo para "Ativo"
                            </small>
                        </div>
                    </div>
                    
                    <!-- Anexos (opcional - para futura implementação) -->
                    <div class="alert alert-secondary mt-3">
                        <i class="fas fa-paperclip"></i>
                        <strong>Anexos:</strong>
                        <p class="mb-0 small">Notas fiscais, laudos e documentos podem ser anexados na página de detalhes após o cadastro.</p>
                    </div>
                    
                    <!-- Botões -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Salvar Manutenção
                        </button>
                        <a href="index.php?controller=manutencao&action=index" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Dicas e Informações -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb"></i> Dicas e Informações
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-tools text-primary"></i> Manutenção Preventiva</h6>
                        <p class="small text-muted">
                            Realizada para evitar problemas futuros. Inclui trocas de óleo, revisões, 
                            alinhamento, balanceamento, etc. Agende com base no tempo ou quilometragem.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-exclamation-triangle text-danger"></i> Manutenção Corretiva</h6>
                        <p class="small text-muted">
                            Realizada para corrigir problemas já existentes. Registre detalhadamente 
                            o problema e as soluções aplicadas para histórico.
                        </p>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="alert alert-success">
                            <i class="fas fa-chart-line"></i>
                            <strong>Benefício:</strong> Manter o histórico de manutenções em dia ajuda a:
                            <ul class="mb-0 mt-1">
                                <li>Aumentar a vida útil do veículo</li>
                                <li>Reduzir custos com reparos emergenciais</li>
                                <li>Planejar o orçamento de manutenção</li>
                                <li>Valorizar o veículo na revenda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Máscara para o campo de custo
    $('#custo').mask('###.###.###.###.##0,00', {
        reverse: true,
        placeholder: '0,00'
    });
    
    // Contador de caracteres da descrição
    $('#descricao').on('input', function() {
        var caracteres = $(this).val().length;
        var restantes = 500 - caracteres;
        $('#contadorCaracteres').html(restantes + ' caracteres restantes');
        
        if(restantes < 0) {
            $('#contadorCaracteres').addClass('text-danger');
        } else if(restantes < 50) {
            $('#contadorCaracteres').addClass('text-warning');
        } else {
            $('#contadorCaracteres').removeClass('text-danger text-warning');
        }
    }).trigger('input');
    
    // Quando selecionar um veículo
    $('#veiculo_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var placa = selectedOption.data('placa');
        var modelo = selectedOption.data('modelo');
        var km = selectedOption.data('km');
        var veiculoId = $(this).val();
        
        if(veiculoId) {
            $('#veiculoSelecionado').html(placa + ' - ' + modelo);
            $('#kmAtualVeiculo').html(parseInt(km).toLocaleString('pt-BR'));
            $('#veiculoInfo').show('slow');
            
            // Preencher automaticamente o KM atual
            if(km) {
                $('#km_atual').val(km);
                $('#km_atual').prop('readonly', true);
                $('#km_atual').css('background-color', '#e9ecef');
            }
            
            // Verificar se o veículo está em manutenção
            var statusText = selectedOption.text();
            if(statusText.includes('manutencao')) {
                $('#km_atual').prop('readonly', false);
                $('#km_atual').css('background-color', '');
                alert('Atenção: Este veículo está em manutenção. Verifique se realmente deseja registrar outra manutenção.');
            }
        } else {
            $('#veiculoInfo').hide('slow');
            $('#km_atual').prop('readonly', false);
            $('#km_atual').css('background-color', '');
        }
    });
    
    // Sugerir próxima manutenção baseada no tipo
    $('#tipo').change(function() {
        var tipo = $(this).val();
        var dataAtual = $('#data_manutencao').val();
        
        if(dataAtual && tipo) {
            var data = new Date(dataAtual);
            var diasAdicionar = 0;
            
            switch(tipo) {
                case 'troca_oleo':
                    diasAdicionar = 180; // 6 meses
                    break;
                case 'revisao':
                    diasAdicionar = 365; // 1 ano
                    break;
                case 'preventiva':
                    diasAdicionar = 90; // 3 meses
                    break;
                case 'pneus':
                    diasAdicionar = 730; // 2 anos
                    break;
                default:
                    diasAdicionar = 0;
            }
            
            if(diasAdicionar > 0 && !$('#proxima_manutencao').val()) {
                data.setDate(data.getDate() + diasAdicionar);
                var dataProxima = data.toISOString().split('T')[0];
                if(confirm('Sugerir próxima manutenção para ' + dataProxima + '?')) {
                    $('#proxima_manutencao').val(dataProxima);
                }
            }
        }
    });
    
    // Validação do formulário
    $('#formManutencao').on('submit', function(e) {
        var veiculo_id = $('#veiculo_id').val();
        var data_manutencao = $('#data_manutencao').val();
        var tipo = $('#tipo').val();
        var descricao = $('#descricao').val();
        var km_atual = $('#km_atual').val();
        
        // Validar veículo
        if(!veiculo_id) {
            alert('Por favor, selecione um veículo');
            $('#veiculo_id').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar data
        if(!data_manutencao) {
            alert('Por favor, informe a data da manutenção');
            $('#data_manutencao').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar tipo
        if(!tipo) {
            alert('Por favor, selecione o tipo de manutenção');
            $('#tipo').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar descrição
        if(descricao.trim() === '') {
            alert('Por favor, preencha a descrição da manutenção');
            $('#descricao').focus();
            e.preventDefault();
            return false;
        }
        
        if(descricao.length > 500) {
            alert('A descrição deve ter no máximo 500 caracteres');
            $('#descricao').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar KM
        if(km_atual === '' || km_atual < 0) {
            alert('Por favor, informe a quilometragem atual do veículo');
            $('#km_atual').focus();
            e.preventDefault();
            return false;
        }
        
        // Confirmar se o custo é muito alto
        var custo = $('#custo').val();
        if(custo) {
            var valorNumerico = parseFloat(custo.replace(/\./g, '').replace(',', '.'));
            if(valorNumerico > 5000) {
                if(!confirm('Atenção: O custo informado é superior a R$ 5.000,00. Deseja continuar?')) {
                    e.preventDefault();
                    return false;
                }
            }
        }
        
        // Confirmar se a data da próxima manutenção é válida
        var proxima = $('#proxima_manutencao').val();
        if(proxima && proxima < data_manutencao) {
            if(!confirm('A data da próxima manutenção é anterior à data atual. Deseja continuar assim?')) {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });
    
    // Botão para limpar formulário (opcional)
    $('#btnLimpar').click(function() {
        if(confirm('Limpar todos os campos do formulário?')) {
            $('#formManutencao')[0].reset();
            $('#veiculoInfo').hide();
            $('#contadorCaracteres').html('');
            $('#custo').val('0,00');
            $('#data_manutencao').val(new Date().toISOString().split('T')[0]);
        }
    });
    
    // Atualizar KM automaticamente quando o veículo muda
    $('#veiculo_id').trigger('change');
    
    // Adicionar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>

<style>
/* Estilos específicos para o formulário de manutenção */
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-label i {
    margin-right: 5px;
    color: #007bff;
}

.card-header i {
    margin-right: 10px;
}

textarea {
    resize: vertical;
}

input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #80bdff;
}

/* Animação para o card de informações do veículo */
#veiculoInfo {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estilo para campos obrigatórios */
.text-danger {
    font-size: 1.1em;
}

/* Botão de submit com animação */
.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Contador de caracteres */
#contadorCaracteres {
    font-size: 0.8em;
    margin-top: 5px;
}

.text-warning {
    color: #ffc107 !important;
}

.text-danger {
    color: #dc3545 !important;
}

/* Responsividade */
@media (max-width: 768px) {
    .btn-lg {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .form-label {
        font-size: 14px;
    }
}

/* Alertas customizados */
.alert-info {
    border-left: 4px solid #0dcaf0;
}

.alert-success {
    border-left: 4px solid #198754;
}

.alert-secondary {
    border-left: 4px solid #6c757d;
}
</style>

<?php include 'views/layout/footer.php'; ?>