<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fas fa-play-circle"></i> Iniciar Utilização de Veículo
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

                <!-- Aviso de segurança -->
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i>
                    <strong>Instruções importantes:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Verifique se o veículo está em boas condições antes de sair</li>
                        <li>Confirme a quilometragem atual no painel do veículo</li>
                        <li>Registre o destino e motivo corretamente para controle</li>
                        <li>Ao retornar, finalize o uso no sistema imediatamente</li>
                    </ul>
                </div>

                <form method="POST" action="index.php?controller=uso&action=create" id="formIniciarUso">
                    <!-- Veículo -->
                    <div class="mb-4">
                        <label for="veiculo_id" class="form-label">
                            <i class="fas fa-car"></i> Veículo <span class="text-danger">*</span>
                        </label>
                        <select class="form-control form-control-lg" id="veiculo_id" name="veiculo_id" required>
                            <option value="">Selecione um veículo disponível</option>
                            <?php if(isset($veiculos) && count($veiculos) > 0): ?>
                                <?php foreach($veiculos as $veiculo): ?>
                                    <option value="<?php echo $veiculo['id']; ?>"
                                            data-placa="<?php echo $veiculo['placa']; ?>"
                                            data-modelo="<?php echo $veiculo['modelo']; ?>"
                                            data-marca="<?php echo $veiculo['marca']; ?>"
                                            data-ano="<?php echo $veiculo['ano']; ?>"
                                            data-cor="<?php echo $veiculo['cor']; ?>"
                                            data-km="<?php echo $veiculo['km_atual']; ?>"
                                            data-status="<?php echo $veiculo['status']; ?>">
                                        <?php echo $veiculo['placa'] . ' - ' . $veiculo['modelo'] . ' (' . $veiculo['marca'] . ')'; ?>
                                        - KM: <?php echo number_format($veiculo['km_atual'], 0, ',', '.'); ?>
                                        <?php if($veiculo['status'] != 'ativo'): ?>
                                            - <span class="text-warning"><?php echo ucfirst($veiculo['status']); ?></span>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Nenhum veículo disponível no momento</option>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Apenas veículos com status "Ativo" podem ser utilizados
                        </small>
                    </div>

                    <!-- Informações do veículo selecionado -->
                    <div id="veiculoInfo" class="mb-4" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle"></i> Informações do Veículo
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small><strong>Placa:</strong> <span id="info_placa"></span></small><br>
                                        <small><strong>Modelo:</strong> <span id="info_modelo"></span></small><br>
                                        <small><strong>Marca:</strong> <span id="info_marca"></span></small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><strong>Ano:</strong> <span id="info_ano"></span></small><br>
                                        <small><strong>Cor:</strong> <span id="info_cor"></span></small><br>
                                        <small><strong>KM Atual:</strong> <span id="info_km"></span> km</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quilometragem de Saída -->
                    <div class="mb-4">
                        <label for="km_saida" class="form-label">
                            <i class="fas fa-tachometer-alt"></i> Quilometragem de Saída <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="km_saida" 
                                   name="km_saida" 
                                   placeholder="0"
                                   min="0"
                                   required>
                            <span class="input-group-text">km</span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-camera"></i> Recomenda-se fotografar o painel para comprovação
                        </small>
                        <div id="km_alerta" class="mt-2" style="display: none;">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                A quilometragem informada é inferior à registrada no sistema. Verifique se está correta.
                            </div>
                        </div>
                    </div>

                    <!-- Data e Hora de Saída -->
                    <div class="mb-4">
                        <label for="data_saida" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Data e Hora de Saída
                        </label>
                        <input type="datetime-local" 
                               class="form-control form-control-lg" 
                               id="data_saida" 
                               name="data_saida" 
                               value="<?php echo date('Y-m-d\TH:i'); ?>"
                               readonly
                               disabled>
                        <small class="text-muted">A data e hora serão registradas automaticamente pelo sistema</small>
                        <input type="hidden" name="data_saida" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    </div>

                    <!-- Destino -->
                    <div class="mb-4">
                        <label for="destino" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Destino
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               id="destino" 
                               name="destino" 
                               placeholder="Ex: Centro, Zona Sul, Outra cidade..."
                               maxlength="200">
                        <small class="text-muted">Informe o local para onde o veículo está se deslocando</small>
                    </div>

                    <!-- Motivo -->
                    <div class="mb-4">
                        <label for="motivo" class="form-label">
                            <i class="fas fa-clipboard-list"></i> Motivo da Utilização
                        </label>
                        <textarea class="form-control" 
                                  id="motivo" 
                                  name="motivo" 
                                  rows="4" 
                                  placeholder="Descreva detalhadamente o motivo da utilização do veículo..."
                                  maxlength="500"></textarea>
                        <div id="contadorCaracteres" class="text-end small text-muted"></div>
                    </div>

                    <!-- Checklist de Segurança -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-clipboard-check"></i> Checklist de Segurança
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check_pneus">
                                <label class="form-check-label" for="check_pneus">
                                    Pneus calibrados e em bom estado
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check_luzes">
                                <label class="form-check-label" for="check_luzes">
                                    Luzes (faróis, setas, freio) funcionando
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check_oleo">
                                <label class="form-check-label" for="check_oleo">
                                    Nível de óleo e água verificado
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check_documentos">
                                <label class="form-check-label" for="check_documentos">
                                    Documentos do veículo estão a bordo
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check_combustivel">
                                <label class="form-check-label" for="check_combustivel">
                                    Nível de combustível suficiente para o trajeto
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Motorista Responsável -->
                    <div class="alert alert-secondary mb-4">
                        <i class="fas fa-user"></i>
                        <strong>Motorista Responsável:</strong> <?php echo $_SESSION['user_nome']; ?>
                        <br>
                        <small class="text-muted">O motorista é responsável pelo veículo durante todo o período de uso</small>
                    </div>

                    <!-- Botões -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg" id="btnSubmit">
                            <i class="fas fa-play"></i> Iniciar Utilização
                        </button>
                        <a href="index.php?controller=uso&action=index" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dicas Úteis -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb"></i> Dicas e Recomendações
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-gas-pump text-primary"></i> Combustível</h6>
                        <p class="small text-muted">
                            Registre o nível de combustível na saída e na chegada. 
                            Isso ajuda a controlar o consumo e identificar possíveis desperdícios.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-mobile-alt text-primary"></i> Contato</h6>
                        <p class="small text-muted">
                            Mantenha o celular sempre disponível. Em caso de problemas,
                            comunique imediatamente a administração da frota.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-file-alt text-primary"></i> Registro Fotográfico</h6>
                        <p class="small text-muted">
                            Tire fotos do veículo antes e depois do uso, especialmente
                            se houver qualquer avaria ou irregularidade.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-clock text-primary"></i> Pontualidade</h6>
                        <p class="small text-muted">
                            Respeite o horário de retorno para não prejudicar outros
                            usuários que possam precisar do veículo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Contador de caracteres do motivo
    $('#motivo').on('input', function() {
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
        var marca = selectedOption.data('marca');
        var ano = selectedOption.data('ano');
        var cor = selectedOption.data('cor');
        var km = selectedOption.data('km');
        var status = selectedOption.data('status');
        
        if($(this).val()) {
            // Preencher informações do veículo
            $('#info_placa').text(placa);
            $('#info_modelo').text(modelo);
            $('#info_marca').text(marca);
            $('#info_ano').text(ano);
            $('#info_cor').text(cor);
            $('#info_km').text(parseInt(km).toLocaleString('pt-BR'));
            
            $('#veiculoInfo').show('slow');
            
            // Preencher KM atual automaticamente
            if(km) {
                $('#km_saida').val(km);
                $('#km_saida').prop('readonly', true);
                $('#km_saida').css('background-color', '#e9ecef');
                $('#km_alerta').hide();
            } else {
                $('#km_saida').prop('readonly', false);
                $('#km_saida').css('background-color', '');
            }
            
            // Verificar se o veículo está disponível
            if(status !== 'ativo') {
                alert('ATENÇÃO: Este veículo não está com status "Ativo". Não é possível iniciar o uso!');
                $('#btnSubmit').prop('disabled', true);
            } else {
                $('#btnSubmit').prop('disabled', false);
            }
        } else {
            $('#veiculoInfo').hide('slow');
            $('#km_saida').prop('readonly', false);
            $('#km_saida').css('background-color', '');
            $('#btnSubmit').prop('disabled', true);
        }
    });
    
    // Validar KM
    $('#km_saida').on('input', function() {
        var kmInformado = parseInt($(this).val());
        var kmSistema = parseInt($('#info_km').text().replace(/\./g, ''));
        
        if(kmInformado && kmSistema && kmInformado < kmSistema) {
            $('#km_alerta').show('slow');
        } else {
            $('#km_alerta').hide('slow');
        }
    });
    
    // Validação do formulário
    $('#formIniciarUso').on('submit', function(e) {
        var veiculoId = $('#veiculo_id').val();
        var kmSaida = $('#km_saida').val();
        var checklistOk = true;
        var checklistItens = [];
        
        // Validar veículo
        if(!veiculoId) {
            alert('Por favor, selecione um veículo');
            $('#veiculo_id').focus();
            e.preventDefault();
            return false;
        }
        
        // Validar KM
        if(!kmSaida || kmSaida < 0) {
            alert('Por favor, informe a quilometragem de saída');
            $('#km_saida').focus();
            e.preventDefault();
            return false;
        }
        
        // Verificar checklist
        if(!$('#check_pneus').is(':checked')) checklistItens.push('- Pneus');
        if(!$('#check_luzes').is(':checked')) checklistItens.push('- Luzes');
        if(!$('#check_oleo').is(':checked')) checklistItens.push('- Nível de óleo/água');
        if(!$('#check_documentos').is(':checked')) checklistItens.push('- Documentos');
        if(!$('#check_combustivel').is(':checked')) checklistItens.push('- Combustível');
        
        if(checklistItens.length > 0) {
            var mensagem = 'ATENÇÃO! Os seguintes itens do checklist não foram verificados:\n\n';
            mensagem += checklistItens.join('\n');
            mensagem += '\n\nDeseja continuar mesmo assim?';
            
            if(!confirm(mensagem)) {
                e.preventDefault();
                return false;
            }
        }
        
        // Confirmar início de uso
        var veiculoTexto = $('#veiculo_id option:selected').text();
        return confirm('Confirmar início de uso do veículo:\n\n' + 
                      'Veículo: ' + veiculoTexto + '\n' +
                      'KM de Saída: ' + parseInt(kmSaida).toLocaleString('pt-BR') + ' km\n\n' +
                      'Você confirma que o veículo está em condições seguras de uso?');
    });
    
    // Botão de submit com loading
    $('#formIniciarUso').on('submit', function() {
        $('#btnSubmit').html('<i class="fas fa-spinner fa-spin"></i> Processando...');
        $('#btnSubmit').prop('disabled', true);
    });
    
    // Destacar veículos disponíveis
    $('#veiculo_id option').each(function() {
        var status = $(this).data('status');
        if(status !== 'ativo') {
            $(this).css('color', '#856404');
            $(this).css('background-color', '#fff3cd');
        }
    });
    
    // Adicionar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Auto completar destino baseado em usos anteriores (opcional)
    $('#destino').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'index.php?controller=uso&action=getDestinos',
                method: 'POST',
                data: { term: request.term },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2
    });
});
</script>

<style>
/* Estilos específicos */
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-label i {
    margin-right: 5px;
    color: #28a745;
}

.card-header i {
    margin-right: 10px;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    border-color: #28a745;
}

.btn-success {
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

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

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.alert-info ul {
    margin-bottom: 0;
    padding-left: 20px;
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
    .form-control-lg, .btn-lg {
        font-size: 14px;
        padding: 8px 12px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .form-label {
        font-size: 14px;
    }
    
    h4 {
        font-size: 18px;
    }
}

/* Checklist */
.form-check {
    padding-left: 2em;
}

.form-check-input {
    margin-left: -2em;
}

/* Animação para o botão */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.btn-success:active {
    transform: scale(0.98);
}

/* Campos obrigatórios */
.text-danger {
    font-size: 1.1em;
}

/* Informações do veículo */
#veiculoInfo .card {
    border-left: 4px solid #28a745;
}

/* Alerta de KM */
#km_alerta {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>

<?php include 'views/layout/footer.php'; ?>