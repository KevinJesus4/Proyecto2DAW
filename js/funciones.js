function mostrarContenido(seccion) {

    if (seccion === 'inicio') {
        window.location.href = '/proyecto/view/menu.php';
    } else {
        $('.container').hide();
        $('#' + seccion).show();

        switch (seccion) {
            case 'marcas':
                obtenerMarcas();
                break;
            case 'modelos':
                obtenerModelos();
                break;
            case 'productos':
                obtenerProductos();
                break;
            case 'clientes':
                obtenerClientes();
                break;
            case 'carrito':
                obtenerCarrito();
                break;
            default:
                break;
        }
    }
}


function obtenerMarcas() {

    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/marca',
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            $('#marcas').empty();

            var tabla = $('<table>').addClass('table');
            var cabecera = $('<thead>').append($('<tr>').append($('<th>').text('ID'), $('<th>').text('Nombre')));
            
            tabla.append(cabecera);
            var cuerpo = $('<tbody>');

            $.each(response, function(index, marca) {
                console.log(marca);
                var fila = $('<tr>').append($('<td>').text(marca.id), $('<td>').text(marca.nombre_marca));

                cuerpo.append(fila);
            });
            tabla.append(cuerpo);
            $('#marcas').append(tabla);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener marcas:', error);
        }
    });
}


function obtenerModelos() {

    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/modelo',
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            $('#modelos').empty();

            var tabla = $('<table>').addClass('table');
            var cabecera = $('<thead>').append($('<tr>').append($('<th>').text('ID'), $('<th>').text('Nombre')));
            
            tabla.append(cabecera);
            var cuerpo = $('<tbody>');

            $.each(response, function(index, modelo) {
                var fila = $('<tr>').append($('<td>').text(modelo.id), $('<td>').text(modelo.nombre_modelo));
                cuerpo.append(fila);
            });
            tabla.append(cuerpo);
            $('#modelos').append(tabla);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener modelos:', error);
        }
    });
}


function obtenerClientes() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta de la API:', response);
            var clientes = response;

            $('#clientes').empty();
            
            var tabla = $('<table>').addClass('table');
            var cabecera = $('<thead>').append($('<tr>').append($('<th>').text('ID'), $('<th>').text('Nombre'), $('<th>').text('Apellido'), $('<th>').text('Correo electrónico')));
            
            tabla.append(cabecera);
            var cuerpo = $('<tbody>');

            $.each(response, function(index, cliente) {
                var fila = $('<tr>').append($('<td>').text(cliente.id), $('<td>').text(cliente.nombreCli), $('<td>').text(cliente.apellido), $('<td>').text(cliente.emailCli));
                cuerpo.append(fila);
            });
            tabla.append(cuerpo);

            $('#clientes').append(tabla);
            var btnRegistrarCliente = $('<button>').attr('id', 'btnRegistrarCliente').addClass('btn btn-primary').text('Registrar Nuevo Cliente');
            
            $('#clientes').append(btnRegistrarCliente);

            $('#btnRegistrarCliente').click(function() {
                window.location.href = '/Proyecto/view/registrar_usuario.php';
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener clientes:', error);
        }
    });
}


$(document).ready(function() {
    obtenerClientes();
});


function crearCliente() {
    $('#formularioCliente').submit(function(event) {
        event.preventDefault(); 

        var nombre = $('#nombre').val();
        var apellido = $('#apellido').val();
        var email = $('#email').val();

        $.ajax({
            url: 'http://localhost/Proyecto/connect/api.php/cliente',
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                apellido: apellido,
                email: email
            },
            success: function(response) {
                alert('Cliente registrado con éxito');
                // Si deseamos tambien podemos reedirigir a menu.php
                // window.location.href = 'menu.php';
            },
            error: function(xhr, status, error) {
                console.error('Error al registrar cliente:', error);
                alert('Error al registrar cliente. Mira la consola para más detalles sobre el error.');
            }
        });
    });
}

$(document).ready(function() {
    crearCliente();

    $('#btnMenu').click(function() {
        window.location.href = '/Proyecto/view/menu.php'; 
    });
});


function clienteSelect(callback) {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta de la API:', response);
            var clientes = response;

            // Llamamos a la función de devolución de llamada con la lista de clientes
            if (typeof callback === 'function') {
                callback(clientes);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener clientes:', error);
        }
    });
}


function obtenerProductos() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/producto',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#productos').empty();

            var table = $('<table>').addClass('table').css('background-color', '#E3E2E2'); // Añadir estilo de fondo de color directamente aquí
            var headerRow = $('<tr>');
            headerRow.append($('<th>').text('ID Producto'));
            headerRow.append($('<th>').text('Marca'));
            headerRow.append($('<th>').text('Modelo'));
            headerRow.append($('<th>').text('Stock'));
            headerRow.append($('<th>').text('Precio Unidad'));
            headerRow.append($('<th>').text('Cliente'));
            headerRow.append($('<th>').text('Cantidad'));
            headerRow.append($('<th>').text('Acción'));
            table.append(headerRow);

            // Llamamos a la función clienteSelect para obtener la lista de clientes
            clienteSelect(function(clientes) {
                $.each(response, function(index, producto) {
                    var row = $('<tr>');
                    row.append($('<td>').text(producto.id));
                    row.append($('<td>').text(producto.nombre_marca));
                    row.append($('<td>').text(producto.nombre_modelo));
                    row.append($('<td>').text(producto.stock));
                    row.append($('<td>').text(producto.precioUnidad));

                    
                    var clienteSelect = $('<select>').attr('id', 'clienteSelect');
                    $.each(clientes, function(index, cliente) {
                        clienteSelect.append($('<option>').attr('value', cliente.id).text(cliente.nombreCli + ' ' + cliente.apellido));
                    });
                    row.append($('<td>').append(clienteSelect));

                    
                    var cantidades = $('<input>').attr('type', 'number').attr('min', 1).attr('max', producto.stock).val(1);
                    row.append($('<td>').append(cantidades));

                    var botonAgregar = $('<button>').text('Agregar al carrito').click(function() {
                        var clienteID = clienteSelect.val();
                        var cantidad = cantidades.val();
                        agregarAlCarrito(producto, clienteID, cantidad);
                    });
                    

                    
                    row.append($('<td>').append(botonAgregar));

                    table.append(row);
                });
            });
            
            $('#productos').append(table);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener productos:', error);
        }
    });
}




function actualizarPrecio() {
    $('#formularioPrecio').submit(function(event) {
        event.preventDefault(); 

        var id_producto = $('#id_producto').val();
        var nuevo_precio = $('#nuevo_precio').val();

        $.ajax({
            url: 'http://localhost/Proyecto/connect/api.php/producto',
            type: 'PUT', 
            dataType: 'json',
            data: {
                id_producto: id_producto,
                precioUnidad: nuevo_precio
            },
            success: function(response) {
                console.log('Respuesta de la API:', response);
                alert('Precio del producto actualizado con éxito');
            },
            error: function(xhr, status, error) {
                console.error('Error al actualizar el precio del producto:', error);
                alert('Error al actualizar el precio del producto. Mira la consola para más detalles sobre el error.');
            }
        });
    });
}

$(document).ready(function() {
    actualizarPrecio();

    $('#btnMenu').click(function() {
        window.location.href = '/Proyecto/view/menu.php'; 
    });
});


function agregarAlCarrito(producto, clienteID, cantidad) {
    var datos = {
        clienteID: clienteID,
        productoID: producto.id,
        cantidad: cantidad
    };

    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/carrito',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(datos), // Convertir datos a JSON
        success: function(response) {
            console.log('Respuesta de la API:', response);
            alert('Producto agregado al carrito exitosamente');
        },
        error: function(xhr, status, error) {
            console.error('Error al agregar producto al carrito:', error);
        }
    });
}

// Dentro de la función obtenerCarrito
function obtenerCarrito() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/carrito',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#carrito').empty();

            if (response.length > 0) {
                var table = $('<table>').addClass('table').css('background-color', '#E3E2E2');
                var headerRow = $('<tr>');
                headerRow.append($('<th>').text('ID'));
                headerRow.append($('<th>').text('Cliente ID'));
                headerRow.append($('<th>').text('Marca'));
                headerRow.append($('<th>').text('Modelo'));
                headerRow.append($('<th>').text('Cantidad'));
                headerRow.append($('<th>').text('Precio Unidad'));
                headerRow.append($('<th>').text('Acción'));
                table.append(headerRow);

                $.each(response, function(index, item) {
                    var row = $('<tr>');
                    row.append($('<td>').text(item.id));
                    row.append($('<td>').text(item.clienteID));
                    row.append($('<td>').text(item.nombre_marca));
                    row.append($('<td>').text(item.nombre_modelo));
                    row.append($('<td>').text(item.cantidad));
                    row.append($('<td>').text(item.precioUnidad));
       
                    var botonEliminar = $('<button>').text('Eliminar').click(function() {
                        carritoID = item.id;
                        eliminarDelCarrito(carritoID); 
                    });
                    row.append($('<td>').append(botonEliminar));
                    table.append(row);
                });
                $('#carrito').append(table);
            } else {
                $('#carrito').text('No hay elementos en el carrito.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener el carrito:', error);
            $('#carrito').text('Error al obtener el carrito.');
        }
    });
}

// Función para eliminar un producto del carrito
function eliminarDelCarrito(carritoID) {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/carrito',
        type: 'DELETE',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ id: carritoID }), 
        success: function(response) {
            console.log('Producto eliminado del carrito:', response);
            alert('Producto eliminado del carrito exitosamente');
            obtenerCarrito();
        },
        error: function(xhr, status, error) {
            console.error('Error al eliminar producto del carrito:', error);
        }
    });
}





















