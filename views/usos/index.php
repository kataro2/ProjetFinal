<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-road"></i> Controle de Uso de Veículos
    </h2>
    <div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalIniciarUso">
            <i class="fas fa-play"></i> Iniciar Uso
        </button>
        <button class="btn btn-info" id="btnAtualizar">
            <i class="fas fa-sync-alt"></i> Atualizar
        </button>
    </div>
</div>

<!-- Cards de Resumo -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Veículos em Uso</h6>
                        <h3 class="mb-0" id="total_em_uso">0</h3>
                    </div>
                    <i class="fas fa-car-side fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Usos Hoje</h6>
                        <h3 class="mb-0" id="usos_hoje">0</h3>
                    </div>
                    <i class="fas fa-calendar-day fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">KM Total Rodado</h6>
                        <h3 class="mb-0" id="km_total">0</h3>
                    </div>
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Média por Uso</h6>
                        <h3 class="mb-0" id="media_km">0</h3>
                    </div>
                    <i class="fas fa-calculator fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-filter"></i> Filtros
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label for="filtro_status" class="form-label">Status</label>
                <select id="filtro_status" class="form-control">
                    <option value="">Todos</option>
                    <option value="em_uso">Em Uso</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="filtro_veiculo" class="form-label">Veículo</label>
                <select id="filtro_veiculo" class="form-control">
                    <option value="">Todos os veículos</option>
                    <?php 
                    if(isset($veiculos) && count($veiculos) > 0):
                        foreach($veiculos as $veiculo):
                    ?>
                    <option value="<?php echo $veiculo['id']; ?>">
                        <?php echo $veiculo['placa'] . ' - ' . $veiculo['modelo']; ?>
                    </option>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="filtro_motorista" class="form-label">Motorista</label>
                <input type="text" id="filtro_motorista" class="form-control" placeholder="Nome do motorista">
            </div>
            <div class="col-md-3 mb-2">
                <label for="filtro_periodo" class="form-label">Período</label>
                <select id="filtro_periodo" class="form-control">
                    <option value="">Todos</option>
                    <option value="hoje">Hoje</option>
                    <option value="ontem">Ontem</option>
                    <option value="semana">Últimos 7 dias</option>
                    <option value="mes">Últimos 30 dias</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 text-end">
                <button class="btn btn-primary" id="btnFiltrar">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <button class="btn btn-secondary" id="btnLimpar">
                    <i class="fas fa-eraser"></i> Limpar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Usos -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Histórico de Utilização
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="tabelaUsos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Veículo</th>
                        <th>Motorista</th>
                        <th>Saída</th>
                        <th>Retorno</th>
                        <th>Destino</th>
                        <th>KM Saída</th>
                        <th>KM Retorno</th>
                        <th>KM Rodado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($usos) && count($usos) > 0): ?>
                        <?php foreach($usos as $uso): ?>
                        <tr data-status="<?php echo $uso['status']; ?>"
                            data-veiculo-id="<?php echo $uso['veiculo_id']; ?>"
                            data-motorista="<?php echo strtolower($uso['usuario_nome']); ?>"
                            data-data="<?php echo date('Y-m-d', strtotime($uso['data_saida'])); ?>">
                            <td><?php echo $uso['id']; ?></td>
                            <td>
                                <?php
                                $statusClass = '';
                                $statusIcon = '';
                                switch($uso['status']) {
                                    case 'em_uso':
                                        $statusClass = 'success';
                                        $statusIcon = 'fa-play-circle';
                                        break;
                                    case 'finalizado':
                                        $statusClass = 'secondary';
                                        $statusIcon = 'fa-check-circle';
                                        break;
                                    case 'cancelado':
                                        $statusClass = 'danger';
                                        $statusIcon = 'fa-times-circle';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>">
                                    <i class="fas <?php echo $statusIcon; ?>"></i>
                                    <?php echo $uso['status'] == 'em_uso' ? 'Em Uso' : ucfirst($uso['status']); ?>
                                </span>
                            </td>
                            <td>
                                <strong><?php echo $uso['placa']; ?></strong><br>
                                <small><?php echo $uso['modelo']; ?></small>
                            </td>
                            <td><?php echo $uso['usuario_nome']; ?></td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($uso['data_saida'])); ?>
                            </td>
                            <td>
                                <?php if($uso['data_retorno']): ?>
                                    <?php echo date('d/m/Y H:i', strtotime($uso['data_retorno'])); ?>
                                <?php else: ?>
                                    <span class="text-warning">Em uso</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo $uso['destino'] ? htmlspecialchars($uso['destino']) : '-'; ?>
                                <?php if($uso['motivo']): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($uso['motivo']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($uso['km_saida'], 0, ',', '.'); ?> km</td>
                            <td>
                                <?php if($uso['km_retorno']): ?>
                                    <?php echo number_format($uso['km_retorno'], 0, ',', '.'); ?> km
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($uso['km_retorno']): ?>
                                    <strong><?php echo number_format($uso['km_retorno'] - $uso['km_saida'], 0, ',', '.'); ?> km</strong>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($uso['status'] == 'em_uso'): ?>
                                    <button class="btn btn-sm btn-warning btn-finalizar" 
                                            data-id="<?php echo $uso['id']; ?>"
                                            data-veiculo="<?php echo $uso['veiculo_id']; ?>"
                                            data-km-saida="<?php echo $uso['km_saida']; ?>">
                                        <i class="fas fa-stop"></i> Finalizar
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-info btn-detalhes" 
                                        data-id="<?php echo $uso['id']; ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDetalhes">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i> Nenhum registro de uso encontrado
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Iniciar Uso -->
<div class="modal fade" id="modalIniciarUso" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-play"></i> Iniciar Utilização de Veículo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=uso&action=create" id="formIniciarUso">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="veiculo_id" class="form-label">
                            <i class="fas fa-car"></i> Veículo <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="veiculo_id" name="veiculo_id" required>
                            <option value="">Selecione um veículo</option>
                            <?php if(isset($veiculos_disponiveis) && count($veiculos_disponiveis) > 0): ?>
                                <?php foreach($veiculos_disponiveis as $veiculo): ?>
                                    <option value="<?php echo $veiculo['id']; ?>"
                                            data-km="<?php echo $veiculo['km_atual']; ?>">
                                        <?php echo $veiculo['placa'] . ' - ' . $veiculo['modelo'] . ' (' . $veiculo['marca'] . ')'; ?>
                                        - KM: <?php echo number_format($veiculo['km_atual'], 0, ',', '.'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Nenhum veículo disponível</option>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">Apenas veículos com status "Ativo"</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="km_saida" class="form-label">
                            <i class="fas fa-tachometer-alt"></i> KM de Saída <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="km_saida" name="km_saida" 
                               placeholder="Quilometragem atual" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="destino" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Destino
                        </label>
                        <input type="text" class="form-control" id="destino" name="destino" 
                               placeholder="Local para onde o veículo está indo">
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo" class="form-label">
                            <i class="fas fa-info-circle"></i> Motivo da Utilização
                        </label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3"
                                  placeholder="Descreva o motivo da utilização do veículo"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-play"></i> Iniciar Uso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Finalizar Uso -->
<div class="modal fade" id="modalFinalizarUso" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-stop"></i> Finalizar Utilização
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?controller=uso&action=finalizar" id="formFinalizarUso">
                <div class="modal-body">
                    <input type="hidden" id="uso_id" name="id">
                    <input type="hidden" id="veiculo_id_finalizar" name="veiculo_id">
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-car"></i> Veículo
                        </label>
                        <p class="form-control-static" id="veiculo_info"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-tachometer-alt"></i> KM na Saída
                        </label>
                        <p class="form-control-static" id="km_saida_info"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="km_retorno" class="form-label">
                            <i class="fas fa-tachometer-alt"></i> KM de Retorno <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="km_retorno" name="km_retorno" 
                               placeholder="Quilometragem no retorno" required>
                        <small id="km_rodado_info" class="text-muted"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-stop"></i> Finalizar Uso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div class="modal fade" id="modalDetalhes" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalhes da Utilização
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetalhesBody">
                <div class="text-center">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-preenchimento do KM ao selecionar veículo
    $('#veiculo_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var km = selectedOption.data('km');
        if(km) {
            $('#km_saida').val(km);
            $('#km_saida').prop('readonly', true);
            $('#km_saida').css('background-color', '#e9ecef');
        } else {
            $('#km_saida').prop('readonly', false);
            $('#km_saida').css('background-color', '');
        }
    }).trigger('change');
    
    // Preparar modal de finalização
    $('.btn-finalizar').click(function() {
        var id = $(this).data('id');
        var veiculoId = $(this).data('veiculo');
        var kmSaida = $(this).data('km-saida');
        var veiculoInfo = $(this).closest('tr').find('td:eq(2)').text().trim();
        
        $('#uso_id').val(id);
        $('#veiculo_id_finalizar').val(veiculoId);
        $('#veiculo_info').html('<strong>' + veiculoInfo + '</strong>');
        $('#km_saida_info').html(parseInt(kmSaida).toLocaleString('pt-BR') + ' km');
        $('#km_retorno').val('');
        $('#km_rodado_info').html('');
        
        $('#modalFinalizarUso').modal('show');
    });
    
    // Calcular KM rodado em tempo real
    $('#km_retorno').on('input', function() {
        var kmRetorno = parseInt($(this).val());
        var kmSaida = parseInt($('#km_saida_info').text());
        
        if(kmRetorno && kmSaida && kmRetorno >= kmSaida) {
            var kmRodado = kmRetorno - kmSaida;
            $('#km_rodado_info').html('<strong class="text-success">KM Rodado: ' + kmRodado.toLocaleString('pt-BR') + ' km</strong>');
        } else if(kmRetorno && kmRetorno < kmSaida) {
            $('#km_rodado_info').html('<strong class="text-danger">KM de retorno não pode ser menor que o KM de saída!</strong>');
        } else {
            $('#km_rodado_info').html('');
        }
    });
    
    // Atualizar cards de resumo
    function atualizarResumos() {
        var emUso = 0;
        var usosHoje = 0;
        var kmTotal = 0;
        var usosFinalizados = 0;
        
        var hoje = new Date().toISOString().split('T')[0];
        
        $('#tabelaUsos tbody tr:visible').each(function() {
            var status = $(this).data('status');
            var kmRodado = parseInt($(this).find('td:eq(9)').text().replace(/[^0-9]/g, ''));
            var dataSaida = $(this).data('data');
            
            if(status === 'em_uso') emUso++;
            if(dataSaida === hoje) usosHoje++;
            if(!isNaN(kmRodado) && status === 'finalizado') {
                kmTotal += kmRodado;
                usosFinalizados++;
            }
        });
        
        $('#total_em_uso').text(emUso);
        $('#usos_hoje').text(usosHoje);
        $('#km_total').text(kmTotal.toLocaleString('pt-BR') + ' km');
        
        var media = usosFinalizados > 0 ? Math.round(kmTotal / usosFinalizados) : 0;
        $('#media_km').text(media.toLocaleString('pt-BR') + ' km');
    }
    
    // Filtrar tabela
    function aplicarFiltros() {
        var status = $('#filtro_status').val();
        var veiculoId = $('#filtro_veiculo').val();
        var motorista = $('#filtro_motorista').val().toLowerCase();
        var periodo = $('#filtro_periodo').val();
        var hoje = new Date().toISOString().split('T')[0];
        var ontem = new Date(Date.now() - 86400000).toISOString().split('T')[0];
        
        $('#tabelaUsos tbody tr').each(function() {
            var mostrar = true;
            var tr = $(this);
            
            if(status && tr.data('status') != status) mostrar = false;
            if(veiculoId && tr.data('veiculo-id') != veiculoId) mostrar = false;
            if(motorista && tr.data('motorista').indexOf(motorista) === -1) mostrar = false;
            
            if(periodo) {
                var data = tr.data('data');
                switch(periodo) {
                    case 'hoje':
                        if(data != hoje) mostrar = false;
                        break;
                    case 'ontem':
                        if(data != ontem) mostrar = false;
                        break;
                    case 'semana':
                        var dataObj = new Date(data);
                        var diasDiff = (new Date() - dataObj) / (1000 * 60 * 60 * 24);
                        if(diasDiff > 7) mostrar = false;
                        break;
                    case 'mes':
                        var dataObj = new Date(data);
                        var diasDiff = (new Date() - dataObj) / (1000 * 60 * 60 * 24);
                        if(diasDiff > 30) mostrar = false;
                        break;
                }
            }
            
            if(mostrar) tr.show();
            else tr.hide();
        });
        
        atualizarResumos();
    }
    
    // Eventos de filtro
    $('#btnFiltrar').click(aplicarFiltros);
    $('#btnLimpar').click(function() {
        $('#filtro_status').val('');
        $('#filtro_veiculo').val('');
        $('#filtro_motorista').val('');
        $('#filtro_periodo').val('');
        $('#tabelaUsos tbody tr').show();
        atualizarResumos();
    });
    
    // Detalhes via AJAX
    $('.btn-detalhes').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'index.php?controller=uso&action=getDetalhes',
            method: 'POST',
            data: {id: id},
            success: function(response) {
                $('#modalDetalhesBody').html(response);
            },
            error: function() {
                $('#modalDetalhesBody').html('<div class="alert alert-danger">Erro ao carregar detalhes</div>');
            }
        });
    });
    
    // Botão atualizar
    $('#btnAtualizar').click(function() {
        location.reload();
    });
    
    // Auto-atualização a cada 30 segundos
    setInterval(function() {
        $.ajax({
            url: window.location.href,
            method: 'GET',
            success: function() {
                location.reload();
            }
        });
    }, 30000);
    
    // Validação do formulário de início de uso
    $('#formIniciarUso').on('submit', function(e) {
        var veiculoId = $('#veiculo_id').val();
        var kmSaida = $('#km_saida').val();
        
        if(!veiculoId) {
            alert('Selecione um veículo');
            e.preventDefault();
            return false;
        }
        
        if(!kmSaida || kmSaida < 0) {
            alert('Informe a quilometragem de saída');
            e.preventDefault();
            return false;
        }
        
        return true;
    });
    
    // Validação do formulário de finalização
    $('#formFinalizarUso').on('submit', function(e) {
        var kmRetorno = $('#km_retorno').val();
        var kmSaida = parseInt($('#km_saida_info').text());
        
        if(!kmRetorno || kmRetorno < 0) {
            alert('Informe a quilometragem de retorno');
            e.preventDefault();
            return false;
        }
        
        if(kmRetorno < kmSaida) {
            alert('O KM de retorno não pode ser menor que o KM de saída!');
            e.preventDefault();
            return false;
        }
        
        return confirm('Confirmar finalização do uso do veículo?');
    });
    
    // Inicializar resumos
    atualizarResumos();
});
</script>

<style>
/* Estilos específicos */
.table th {
    cursor: pointer;
    user-select: none;
}

.table th:hover {
    background-color: #e9ecef;
}

.badge {
    font-size: 0.85em;
    padding: 5px 10px;
}

.btn-sm {
    margin: 0 2px;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

/* Animação para linhas em uso */
tr[data-status="em_uso"] {
    background-color: rgba(40, 167, 69, 0.05);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        background-color: rgba(40, 167, 69, 0.05);
    }
    50% {
        background-color: rgba(40, 167, 69, 0.1);
    }
    100% {
        background-color: rgba(40, 167, 69, 0.05);
    }
}

/* Responsividade */
@media (max-width: 768px) {
    .table {
        font-size: 12px;
    }
    
    .btn-sm {
        padding: 3px 6px;
        font-size: 10px;
    }
    
    .badge {
        font-size: 0.75em;
        padding: 3px 6px;
    }
    
    h3 {
        font-size: 1.2rem;
    }
}

/* Modal de finalização */
#km_rodado_info {
    display: block;
    margin-top: 5px;
    font-weight: bold;
}

/* Tooltips */
[data-bs-toggle="tooltip"] {
    cursor: help;
}
</style>

<?php include 'views/layout/footer.php'; ?>