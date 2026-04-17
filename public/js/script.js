$(document).ready(function() {
    // Máscara para placa
    $('#placa').mask('AAA-9999');
    
    // Máscara para valores monetários
    $('.money').mask('#.##0,00', {reverse: true});
    
    // Confirmação antes de excluir
    $('.btn-delete').click(function(e) {
        if(!confirm('Tem certeza que deseja excluir este registro?')) {
            e.preventDefault();
        }
    });
    
    // Auto complete para KM
    $('#veiculo_id').change(function() {
        var veiculoId = $(this).val();
        if(veiculoId) {
            $.ajax({
                url: 'index.php?controller=veiculo&action=getKm',
                method: 'POST',
                data: {id: veiculoId},
                success: function(data) {
                    $('#km_atual').val(data);
                }
            });
        }
    });
    
    // Validação de formulário
    $('form').submit(function() {
        var valid = true;
        $(this).find('input[required], select[required]').each(function() {
            if(!$(this).val()) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if(!valid) {
            alert('Por favor, preencha todos os campos obrigatórios!');
            return false;
        }
        
        return true;
    });
    
    // Atualização automática de status
    function atualizarStatus() {
        $.ajax({
            url: 'index.php?controller=uso&action=checkStatus',
            method: 'GET',
            success: function(data) {
                // Atualiza os status na tabela
                location.reload();
            }
        });
    }
    
    // Atualizar a cada 30 segundos
    setInterval(atualizarStatus, 30000);
});