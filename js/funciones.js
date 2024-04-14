$(document).ready(function () {
    $('#loginForm').on('submit', function (event) {
        event.preventDefault();
        const usuario = $('#usuario').val();
        const clave = $('#clave').val();
        if (!usuario || !clave) {
            alert('Es necesario completar todos los campos');
            return; 
        }
    });

    $('#logoutLink').on('click', function (event) {
        event.preventDefault();
        $('#logoutForm').submit();
    });
});


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
                var fila = $('<tr>').append($('<td>').text(marca.id_marca), $('<td>').text(marca.nombre_marca));
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
                var fila = $('<tr>').append($('<td>').text(modelo.id_modelo), $('<td>').text(modelo.nombre_modelo));
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
                var fila = $('<tr>').append($('<td>').text(cliente.id_cliente), $('<td>').text(cliente.nombreCli), $('<td>').text(cliente.apellido), $('<td>').text(cliente.emailCli));
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
                    row.append($('<td>').text(producto.id_producto));
                    row.append($('<td>').text(producto.nombre_marca));
                    row.append($('<td>').text(producto.nombre_modelo));
                    row.append($('<td>').text(producto.stock));
                    row.append($('<td>').text(producto.precioUnidad));

                    // Creamos el elemento select con las opciones de clientes
                    var clienteSelect = $('<select>').attr('id', 'clienteSelect');
                    $.each(clientes, function(index, cliente) {
                        clienteSelect.append($('<option>').attr('value', cliente.id_cliente).text(cliente.nombreCli + ' ' + cliente.apellido));
                    });
                    row.append($('<td>').append(clienteSelect));

                    // Campo de entrada para la cantidad
                    var cantidad = $('<input>').attr('type', 'number').attr('min', 1).attr('max', producto.stock).val(1);
                    row.append($('<td>').append(cantidad));

                    // Botón para agregar al carrito
                    var botonAgregar = $('<button>').text('Agregar al carrito').click(function() {
                        var cliente = clienteSelect.val();
                        agregarAlCarrito(cliente, producto.nombre_marca, producto.nombre_modelo, cantidad.val());
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


function agregarAlCarrito(cliente, marca, modelo, cantidad) {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/carrito',
        type: 'POST',
        dataType: 'json',
        data: {
            cliente: cliente,
            marca: marca,
            modelo: modelo,
            cantidad: cantidad
        },
        success: function(response) {
            console.log('Respuesta de la API:', response);
            alert('Producto agregado al carrito exitosamente');
            
            $('#carrito').show();
           
            var carrito = new Carrito();
            carrito.agregarAlCarrito(cliente, marca, modelo, cantidad);
        },
        error: function(xhr, status, error) {
            console.error('Error al agregar el producto al carrito:', error);
            alert('Error al agregar el producto al carrito. Mira la consola para más detalles sobre el error.');
        }
    });
}









