<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-car"></i> Veículos</h2>
    <?php if($_SESSION['user_cargo'] == 'admin'): ?>
    <a href="index.php?controller=veiculo&action=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Veículo
    </a>
    <?php endif; ?>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Ano</th>
                <th>Cor</th>
                <th>KM Atual</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($veiculos as $veiculo): ?>
            <tr>
                <td><?php echo $veiculo['id']; ?></td>
                <td><?php echo $veiculo['placa']; ?></td>
                <td><?php echo $veiculo['modelo']; ?></td>
                <td><?php echo $veiculo['marca']; ?></td>
                <td><?php echo $veiculo['ano']; ?></td>
                <td><?php echo $veiculo['cor']; ?></td>
                <td><?php echo number_format($veiculo['km_atual'], 0, ',', '.'); ?> km</td>
                <td>
                    <?php
                    $statusClass = '';
                    switch($veiculo['status']) {
                        case 'ativo':
                            $statusClass = 'success';
                            break;
                        case 'manutencao':
                            $statusClass = 'warning';
                            break;
                        case 'em_uso':
                            $statusClass = 'info';
                            break;
                        default:
                            $statusClass = 'secondary';
                    }
                    ?>
                    <span class="badge bg-<?php echo $statusClass; ?>">
                        <?php echo ucfirst($veiculo['status']); ?>
                    </span>
                </td>
                <td>
                    <a href="index.php?controller=veiculo&action=show&id=<?php echo $veiculo['id']; ?>" 
                       class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    <?php if($_SESSION['user_cargo'] == 'admin'): ?>
                    <a href="index.php?controller=veiculo&action=edit&id=<?php echo $veiculo['id']; ?>" 
                       class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="index.php?controller=veiculo&action=delete&id=<?php echo $veiculo['id']; ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Tem certeza que deseja excluir este veículo?')">
                        <i class="fas fa-trash"></i>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/layout/footer.php'; ?>