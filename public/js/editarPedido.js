// Abrir modal con datos
$(document).on('click', '.btn-editar-pedido', function() {
    const id = $(this).data('id');
    $.ajax({
        url: `/dashboard/pedidos/${id}/detalles`,
        type: 'GET',
        success: function(response) {
            $('#editar_id_pedido').val(response.pedido.id);
            $('#editar_cliente').val(response.pedido.cliente);
            $('#editar_total').val(response.pedido.total);
            $('#editar_estado').val(response.pedido.estado);
            $('#editar_tipo_entrega').val(response.pedido.tipo_entrega);
            $('#editar_direccion').val(response.pedido.direccion);
        },
        error: function() {
            alert('Error al cargar los datos del pedido.');
        }
    });
});

// Ocultar campo dirección si no es domicilio
$('#editar_tipo_entrega').on('change', function() {
    if ($(this).val() === 'domicilio') {
        $('#grupo_direccion').show();
    } else {
        $('#grupo_direccion').hide();
    }
});

// Enviar formulario de edición
$('#formEditarPedido').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: "{{ route('pedidos.actualizar') }}",
        method: "POST",
        data: formData,
        success: function(resp) {
            $('#editarPedidoModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert('Error al guardar los cambios.');
        }
    });
});
