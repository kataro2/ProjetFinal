<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Detalhes do Veículo</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Placa:</strong> <?php echo $this->veiculo->placa; ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Modelo:</strong> <?php echo $this->veiculo->modelo; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Marca:</strong> <?php echo $this->veiculo->marca; ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Ano:</strong> <?php echo $this->veiculo->ano; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Cor:</strong> <?php echo $this->veiculo->cor; ?>
                    </div>
                    <div class="col-md-6">
                        <strong>KM Atual:</strong> <?php echo number_format($this->veiculo->km_atual, 0, ',', '.'); ?> km
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <span class="badge bg-<?php echo $this->veiculo->status == 'ativo' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($this->veiculo->status); ?>
                        </span>
                    </div>
                </div>
                
                <hr>
                <h5>Histórico de Manutenções</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Custo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($manutencao = $manutencoes->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($manutencao['data_manutencao'])); ?></td>
                                <td><?php echo $manutencao['tipo']; ?></td>
                                <td>R$ <?php echo number_format($manutencao['custo'], 2, ',', '.'); ?></td>
                                <td><?php echo ucfirst($manutencao['status']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <a href="index.php?controller=veiculo&action=index" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>