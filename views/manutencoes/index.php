<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-tools"></i> Manutenções
    </h2>
    <?php if($_SESSION['user_cargo'] == 'admin'): ?>
    <a href="index.php?controller=manutencao&action=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nova Manutenção
    </a>
    <?php endif; ?>
</div>

<!-- Filtros e Busca -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-filter"></i> Filtros de Busca
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label for="filtro_veiculo" class="form-label">Veículo</label>
                <select id="filtro_veiculo" class="form-control">
                    <option value="">Todos os veículos</option>
                    <?php 
                    // Buscar veículos para o filtro
                    $veiculo = new Veiculo();
                    $stmt_veiculos = $veiculo->readAll();
                    $veiculos_lista = $stmt_veiculos->fetchAll(PDO::FETCH_ASSOC);
                    foreach($veiculos_lista as $v): 
                    ?>
                    <option value="<?php echo $v['id']; ?>">
                        <?php echo $v['placa'] . ' - ' . $v['modelo']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="filtro_tipo" class="form-label">Tipo</label>
                <select id="filtro_tipo" class="form-control">
                    <option value="">Todos os tipos</option>
                    <option value="preventiva">Preventiva</option>
                    <option value="corretiva">Corretiva</option>
                    <option value="revisao">Revisão</option>
                    <option value="troca_oleo">Troca de Óleo</option>
                    <option value="outros">Outros</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="filtro_status" class="form-label">Status</label>
                <select id="filtro_status" class="form-control">
                    <option value="">Todos os status</option>
                    <option value="agendada">Agendada</option>
                    <option value="realizada">Realizada</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="filtro_periodo" class="form-label">Período</label>
                <select id="filtro_periodo" class="form-control">
                    <option value="">Todos os períodos</option>
                    <option value="7">Últimos 7 dias</option>
                    <option value="30">Últimos 30 dias</option>
                    <option value="90">Últimos 90 dias</option>
                    <option value="365">Último ano</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 text-end">
                <button class="btn btn-primary" id="btnFiltrar">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <button class="btn btn-secondary" id="btnLimpar">
                    <i class="fas fa-eraser"></i> Limpar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cards de Resumo -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total de Manutenções</h6>
                        <h3 class="mb-0" id="total_manutencoes">0</h3>
                    </div>
                    <i class="fas fa-tools fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Custo Total</h6>
                        <h3 class="mb-0" id="custo_total">R$ 0,00</h3>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Manutenções Preventivas</h6>
                        <h3 class="mb-0" id="total_preventivas">0</h3>
                    </div>
                    <i class="fas fa-shield-alt fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Manutenções Corretivas</h6>
                        <h3 class="mb-0" id="total_corretivas">0</h3>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Manutenções -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Lista de Manutenções
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="tabelaManutencoes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Veículo</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Custo</th>
                        <th>KM</th>
                        <th>Próxima Manutenção</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($manutencoes) && count($manutencoes) > 0): ?>
                        <?php foreach($manutencoes as $manutencao): ?>
                        <tr data-veiculo-id="<?php echo $manutencao['veiculo_id']; ?>" 
                            data-tipo="<?php echo $manutencao['tipo']; ?>"
                            data-status="<?php echo $manutencao['status']; ?>">
                            <td><?php echo $manutencao['id']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($manutencao['data_manutencao'])); ?></td>
                            <td>
                                <strong><?php echo $manutencao['placa']; ?></strong><br>
                                <small><?php echo $manutencao['modelo']; ?></small>
                            </td>
                            <td>
                                <?php
                                $tipoIcon = '';
                                $tipoClass = '';
                                switch($manutencao['tipo']) {
                                    case 'preventiva':
                                        $tipoIcon = 'fa-shield-alt';
                                        $tipoClass = 'info';
                                        break;
                                    case 'corretiva':
                                        $tipoIcon = 'fa-exclamation-triangle';
                                        $tipoClass = 'danger';
                                        break;
                                    case 'revisao':
                                        $tipoIcon = 'fa-clipboard-list';
                                        $tipoClass = 'success';
                                        break;
                                    default:
                                        $tipoIcon = 'fa-tools';
                                        $tipoClass = 'secondary';
                                }
                                ?>
                                <span class="badge bg-<?php echo $tipoClass; ?>">
                                    <i class="fas <?php echo $tipoIcon; ?>"></i>
                                    <?php echo ucfirst($manutencao['tipo']); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $descricao = $manutencao['descricao'];
                                echo strlen($descricao) > 50 ? substr($descricao, 0, 50) . '...' : $descricao;
                                ?>
                            </td>
                            <td class="text-end">
                                <strong>R$ <?php echo number_format($manutencao['custo'], 2, ',', '.'); ?></strong>
                            </td>
                            <td><?php echo number_format($manutencao['km_atual'], 0, ',', '.'); ?> km</td>
                            <td>
                                <?php if($manutencao['proxima_manutencao']): ?>
                                    <?php 
                                    $proxima = strtotime($manutencao['proxima_manutencao']);
                                    $hoje = time();
                                    $dias = ceil(($proxima - $hoje) / (60 * 60 * 24));
                                    $alertClass = '';
                                    if($dias <= 7) $alertClass = 'danger';
                                    elseif($dias <= 30) $alertClass = 'warning';
                                    else $alertClass = 'success';
                                    ?>
                                    <span class="badge bg-<?php echo $alertClass; ?>">
                                        <?php echo date('d/m/Y', $proxima); ?>
                                        <?php if($dias > 0 && $dias <= 30): ?>
                                            <br><small>(em <?php echo $dias; ?> dias)</small>
                                        <?php elseif($dias <= 0): ?>
                                            <br><small>(atrasada)</small>
                                        <?php endif; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Não programada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusClass = '';
                                $statusIcon = '';
                                switch($manutencao['status']) {
                                    case 'agendada':
                                        $statusClass = 'warning';
                                        $statusIcon = 'fa-clock';
                                        break;
                                    case 'realizada':
                                        $statusClass = 'success';
                                        $statusIcon = 'fa-check-circle';
                                        break;
                                    case 'cancelada':
                                        $statusClass = 'danger';
                                        $statusIcon = 'fa-times-circle';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>">
                                    <i class="fas <?php echo $statusIcon; ?>"></i>
                                    <?php echo ucfirst($manutencao['status']); ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-detalhes" 
                                        data-id="<?php echo $manutencao['id']; ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDetalhes">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if($_SESSION['user_cargo'] == 'admin' && $manutencao['status'] == 'agendada'): ?>
                                <button class="btn btn-sm btn-success btn-realizar" 
                                        data-id="<?php echo $manutencao['id']; ?>">
                                    <i class="fas fa-check"></i> Realizar
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i> Nenhuma manutenção cadastrada
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Detalhes da Manutenção -->
<div class="modal fade" id="modalDetalhes" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-tools"></i> Detalhes da Manutenção
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetalhesBody">
                <!-- Conteúdo carregado via AJAX -->
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
    // Função para atualizar os cards de resumo
    function atualizarResumos() {
        var total = 0;
        var custoTotal = 0;
        var preventivas = 0;
        var corretivas = 0;
        
        $('#tabelaManutencoes tbody tr:visible').each(function() {
            total++;
            var custo = parseFloat($(this).find('td:eq(5)').text().replace('R$ ', '').replace('.', '').replace(',', '.'));
            if(!isNaN(custo)) custoTotal += custo;
            
            var tipo = $(this).find('td:eq(3) .badge').text().trim().toLowerCase();
            if(tipo === 'preventiva') preventivas++;
            if(tipo === 'corretiva') corretivas++;
        });
        
        $('#total_manutencoes').text(total);
        $('#custo_total').text('R$ ' + custoTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2}));
        $('#total_preventivas').text(preventivas);
        $('#total_corretivas').text(corretivas);
    }
    
    // Função de filtro
    function aplicarFiltros() {
        var veiculoId = $('#filtro_veiculo').val();
        var tipo = $('#filtro_tipo').val();
        var status = $('#filtro_status').val();
        var periodo = $('#filtro_periodo').val();
        
        $('#tabelaManutencoes tbody tr').each(function() {
            var mostrar = true;
            
            if(veiculoId && $(this).data('veiculo-id') != veiculoId) {
                mostrar = false;
            }
            
            if(tipo && $(this).data('tipo') != tipo) {
                mostrar = false;
            }
            
            if(status && $(this).data('status') != status) {
                mostrar = false;
            }
            
            if(periodo) {
                var dataStr = $(this).find('td:eq(1)').text();
                var partes = dataStr.split('/');
                var dataManutencao = new Date(partes[2], partes[1]-1, partes[0]);
                var hoje = new Date();
                var diasDiferenca = Math.floor((hoje - dataManutencao) / (1000 * 60 * 60 * 24));
                
                if(diasDiferenca > periodo) {
                    mostrar = false;
                }
            }
            
            if(mostrar) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        atualizarResumos();
    }
    
    // Botão Filtrar
    $('#btnFiltrar').click(function() {
        aplicarFiltros();
    });
    
    // Botão Limpar Filtros
    $('#btnLimpar').click(function() {
        $('#filtro_veiculo').val('');
        $('#filtro_tipo').val('');
        $('#filtro_status').val('');
        $('#filtro_periodo').val('');
        $('#tabelaManutencoes tbody tr').show();
        atualizarResumos();
    });
    
    // Detalhes da manutenção via AJAX
    $('.btn-detalhes').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'index.php?controller=manutencao&action=getDetalhes',
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
    
    // Realizar manutenção
    $('.btn-realizar').click(function() {
        if(confirm('Confirmar que esta manutenção foi realizada?')) {
            var id = $(this).data('id');
            $.ajax({
                url: 'index.php?controller=manutencao&action=realizar',
                method: 'POST',
                data: {id: id},
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert('Erro ao realizar manutenção');
                    }
                },
                error: function() {
                    alert('Erro ao processar requisição');
                }
            });
        }
    });
    
    // Inicializar resumos
    atualizarResumos();
    
    // Adicionar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Exportar para Excel
    $('#btnExportarExcel').click(function() {
        var tabela = document.getElementById('tabelaManutencoes');
        var html = tabela.outerHTML;
        var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        var link = document.createElement('a');
        link.href = url;
        link.download = 'manutencoes.xls';
        link.click();
    });
    
    // Ordenação da tabela
    $('th').click(function() {
        var index = $(this).index();
        var rows = $('#tabelaManutencoes tbody tr').get();
        
        rows.sort(function(a, b) {
            var valA = $(a).find('td:eq(' + index + ')').text();
            var valB = $(b).find('td:eq(' + index + ')').text();
            
            if($.isNumeric(valA.replace(',', '.'))) {
                valA = parseFloat(valA.replace(',', '.'));
                valB = parseFloat(valB.replace(',', '.'));
            }
            
            if(valA < valB) return -1;
            if(valA > valB) return 1;
            return 0;
        });
        
        if($(this).hasClass('sorted-asc')) {
            rows.reverse();
            $(this).removeClass('sorted-asc').addClass('sorted-desc');
        } else {
            $(this).removeClass('sorted-desc').addClass('sorted-asc');
        }
        
        $.each(rows, function(index, row) {
            $('#tabelaManutencoes tbody').append(row);
        });
    });
});
</script>

<style>
/* Estilos específicos para a página de manutenções */
.table th {
    cursor: pointer;
    user-select: none;
}

.table th:hover {
    background-color: #e9ecef;
}

.table th.sorted-asc::after {
    content: " ↑";
}

.table th.sorted-desc::after {
    content: " ↓";
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-group-sm > .btn, .btn-sm {
    margin: 0 2px;
}

.badge {
    font-size: 0.85em;
    padding: 5px 10px;
}

.modal-lg {
    max-width: 800px;
}

.fa-2x {
    opacity: 0.8;
}

/* Estilo para linhas com manutenção atrasada */
tr:has(td:contains("atrasada")) {
    background-color: rgba(220, 53, 69, 0.05);
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
    
    .card-body h3 {
        font-size: 1.2rem;
    }
}

/* Animação para os cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.5s ease-out;
}
</style>

<?php include 'views/layout/footer.php'; ?>