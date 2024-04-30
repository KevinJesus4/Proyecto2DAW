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
            case 'comprar':
                obtenerCompras();
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

            var tabla = $('<table>').addClass('table').css('background-color', '#EBEDEF');
            var cabecera = $('<thead>').append($('<tr>').append($('<th>').text('ID'), $('<th>').text('Nombre')));
            tabla.append(cabecera);
            var cuerpo = $('<tbody>');

            $.each(response, function(index, marca) {
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


function selectMarcaID() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/marca',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#marcaID').empty();
            $.each(response, function(index, marca) {
                $('#marcaID').append($('<option>').val(marca.id).text(marca.nombre_marca));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener marcas:', error);
        }
    });
}


$(document).ready(function() {
    selectMarcaID();
});


function obtenerModelos() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/modelo',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#modelos').empty();

            var filtroId = $('<input>').attr('type', 'text').attr('id', 'idFiltroModelo').addClass('form-control').attr('placeholder', 'Filtrar por ID Modelo');
            var botonFiltrar = $('<button>').addClass('btn btn-outline-primary').text('Filtrar').click(function() {
                var idFiltro = $('#idFiltroModelo').val();
                filtrarModelosPorId(idFiltro);
            });

            var btnAgregarModelo = $('<button>').attr('id', 'btnAgregarModelo').addClass('btn btn-outline-primary').text('Agregar Modelo');
            $('#modelos').append(
                $('<div>').addClass('form-group row').append(
                    $('<div>').addClass('col').append(
                        filtroId
                    ),
                    $('<div>').addClass('col-auto').append(
                        botonFiltrar
                    ),
                    $('<div>').addClass('col-auto').append(
                        btnAgregarModelo
                    )
                ),
            );

            var tabla = $('<table>').addClass('table').css('background-color', '#EBEDEF');
            var cabecera = $('<thead>').append(
                $('<tr>').append(
                    $('<th>').text('ID Modelo'),
                    $('<th>').text('Nombre')
                )
            );
            tabla.append(cabecera);
            var cuerpo = $('<tbody>');

            $.each(response, function(index, modelo) {
                var fila = $('<tr>').append(
                    $('<td>').text(modelo.id),
                    $('<td>').text(modelo.nombre_modelo)
                );
                cuerpo.append(fila);
            });

            tabla.append(cuerpo);
            $('#modelos').append(tabla);

            $('#btnAgregarModelo').click(function() {
                window.location.href = '/Proyecto/view/agregar_modelo.php';
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener modelos:', error);
        }
    });
}


function filtrarModelosPorId(modeloID) {
    var filas = $('#modelos tbody tr');
    filas.hide();
    filas.each(function() {
        var id = $(this).find('td:first').text();
        if (id === modeloID) {
            $(this).show();
        }
    });
}


function selectModeloID() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/modelo',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#modeloID').empty();
            $.each(response, function(index, modelo) {
                $('#modeloID').append($('<option>').val(modelo.id).text(modelo.nombre_modelo));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener modelos:', error);
        }
    });
}


$(document).ready(function() {
    selectModeloID();
});


function agregarModelo() {
    $('#formAgregarModelo').submit(function(event) { 
        event.preventDefault(); 

        var nombre_modelo = $('#nombre_modelo').val();
        var datos = {
            nombre_modelo: nombre_modelo,
        };

        $.ajax({
            url: 'http://localhost/Proyecto/connect/api.php/modelo',
            type: 'POST', 
            dataType: 'json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response.hasOwnProperty('error')) {
                    console.error('Error al agregar el modelo:', response.error);
                    //alert('Error al agregar el modelo. Consulta la consola para más detalles sobre el error.');
                } else {
                    alert('Modelo agregado exitosamente:', response.mensaje);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al agregar el modelo:', error);
                alert('Error al agregar el modelo. Consulta la consola para más detalles sobre el error.');
            }
        });
    });
}


$(document).ready(function() {
    agregarModelo();
});


function obtenerClientes() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var clientes = response;
            $('#clientes').empty();
            
            var tabla = $('<table>').addClass('table').css('background-color', '#EBEDEF');
            var cabecera = $('<thead>').append($('<tr>').append($('<th>').text('ID'), $('<th>').text('Nombre'), $('<th>').text('Apellido'), $('<th>').text('Correo electrónico'), $('<th>').text('Acciones')));
            
            tabla.append(cabecera);
            var cuerpo = $('<tbody>');

            $.each(response, function(index, cliente) {
                var fila = $('<tr>').append(
                    $('<td>').text(cliente.id),
                    $('<td>').text(cliente.nombreCli),
                    $('<td>').text(cliente.apellido),
                    $('<td>').text(cliente.emailCli),
                    $('<td>').append(
                        $('<button>').text('Eliminar').addClass('btn btn-outline-danger').click(function() {
                            if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
                                eliminarCliente(cliente.id);
                            }
                        })
                    )
                );
                cuerpo.append(fila);
            });

            tabla.append(cuerpo);

            $('#clientes').append(tabla);
            var btnRegistrarCliente = $('<button>').attr('id', 'btnRegistrarCliente').addClass('btn btn-outline-primary').text('Registrar Cliente');
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



function eliminarCliente(idCliente) {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/cliente',
        type: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({ clienteID: idCliente }),
        success: function(response) {
            console.log('Cliente eliminado:', response);
            obtenerClientes();
        },
        error: function(xhr, status, error) {
            alert('Error al eliminar cliente:', error);
        }
    });
}


function registrarCliente() {
    //console.log('La función registrarCliente() se está ejecutando correctamente.');

    $('#formularioCliente').submit(function(event) {
        event.preventDefault(); 
        var nombreCli = $('#nombreCli').val();
        var apellido = $('#apellido').val();
        var emailCli = $('#emailCli').val();

        if (nombreCli === '' || apellido === '' || emailCli === '') {
            console.error('Error: Todos los campos deben estar rellenos..');
            alert('Error: Todos los campos deben estar rellenos..');
            return;
        }

        var datos ={
            nombreCli: nombreCli, 
            apellido: apellido, 
            emailCli: emailCli
        };

        console.log('Datos del formulario:', nombreCli, apellido, emailCli);

        $.ajax({
            url: 'http://localhost/Proyecto/connect/api.php/cliente',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(datos),
            success: function(response) {
                console.log('Cliente creado con éxito', response);
            },
            error: function(xhr, status, error) {
                alert('Cliente creado con éxito');
            }
        });
    });
}


$(document).ready(function() {
    registrarCliente(); 
    //window.location.href = '/Proyecto/view/menu.php';
});



function clienteSelect(callback) {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/cliente',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta de la API:', response);
            var clientes = response;

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

            var filtroId = $('<input>').attr('type', 'text').attr('id', 'idFiltro').addClass('form-control').attr('placeholder', 'Filtrar Producto por su ID');
            var botonFiltrar = $('<button>').addClass('btn btn-outline-primary').text('Filtrar').click(function() {
                var idFiltro = $('#idFiltro').val();
                filtrarProductosPorId(idFiltro);
            });

            var botonNuevoProducto = $('<button>').attr('id', 'mostrarFormulario').addClass('btn btn-outline-primary').text('Nuevo Producto');
            var botonActualizarPrecio = $('<button>').attr('id', 'btnCambiarPrecio').addClass('btn btn-outline-primary').text('Cambiar Precio');
            
            $('#productos').append(
                $('<div>').addClass('form-group row').append(
                    $('<div>').addClass('col').append(
                        filtroId
                    ),
                    $('<div>').addClass('col-auto').append(
                        botonFiltrar
                    ),
                    $('<div>').addClass('col-auto').append(
                        botonNuevoProducto,
                    ),
                    $('<div>').addClass('col-auto').append(
                        botonActualizarPrecio
                    )
                ),
                $('<br>')
            );

            var tabla = $('<table>').addClass('table').css('background-color', '#EBEDEF');
            var cabecera = $('<thead>').append(
                $('<tr>').append(
                    $('<th>').text('ID Producto'),
                    $('<th>').text('Marca'),
                    $('<th>').text('Modelo'),
                    $('<th>').text('Stock'),
                    $('<th>').text('Precio Unidad'),
                    $('<th>').text('Acciones')
                )
            );
            tabla.append(cabecera);

            var cuerpo = $('<tbody>');
            response.forEach(function(producto) {
                var row = $('<tr>').append(
                    $('<td>').text(producto.id),
                    $('<td>').text(producto.nombre_marca),
                    $('<td>').text(producto.nombre_modelo),
                    $('<td>').text(producto.stock),
                    $('<td>').text(producto.precioUnidad + ' €')
                );

                var botonEliminar = $('<button>').addClass('btn btn-outline-danger').text('Eliminar').click(function() {
                    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                        eliminarProducto(producto.id);
                    }
                });
                row.append($('<td>').append(botonEliminar));
                cuerpo.append(row);
            });

            tabla.append(cuerpo);
            $('#productos').append(tabla);

            botonNuevoProducto.click(function() {
                window.location.href = '/Proyecto/view/agregar_producto.php';
            });

            botonActualizarPrecio.click(function() {
                window.location.href = '/Proyecto/view/actualizar_precio.php';
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener productos:', error);
        }
    });
}


function filtrarProductosPorId(productoID) {
    var filas = $('#productos tbody tr');
    filas.hide();
    filas.each(function() {
        var id = $(this).find('td:first').text(); 
        if (id === productoID) {
            $(this).show();
        }
    });
}


function eliminarProducto(productoID) {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/producto',
        type: 'DELETE',
        data: JSON.stringify({ productoID: productoID }),
        dataType: 'json',
        contentType: 'application/json',
        success: function(response) {
            obtenerProductos();
            console.log('Producto eliminado exitosamente');
        },
        error: function(xhr, status, error) {
            console.error('Error al eliminar producto:', error);
        }
    });
}


function agregarProducto() {
    $('#formAgregarProducto').submit(function(event) { 
        event.preventDefault(); 

        var marcaID = $('#marcaID').val();
        var modeloID = $('#modeloID').val();
        var stock = $('#stock').val();
        var precioUnidad = $('#precioUnidad').val();
        
        var datos = {
            marcaID: marcaID,
            modeloID: modeloID,
            stock: stock,
            precioUnidad: precioUnidad
        };

        $.ajax({
            url: 'http://localhost/Proyecto/connect/api.php/producto',
            type: 'POST', 
            dataType: 'json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response.hasOwnProperty('error')) {
                    console.error('Error al agregar el nuevo producto:', response.error);
                    alert('Error al agregar el nuevo producto: ' + response.error);
                } else {
                    console.log('Nuevo producto agregado exitosamente:', response.mensaje);
                    alert('Nuevo producto agregado exitosamente: ' + response.mensaje);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al agregar el nuevo producto:', error);
                alert('Error al agregar el nuevo producto. Consulta la consola para más detalles sobre el error.');
            }
        });
    });
}


$(document).ready(function() {
    agregarProducto();
});
$('#btnMenu').click(function() {
    window.location.href = '/Proyecto/view/menu.php'; 
});


function obtenerCompras() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/producto',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#comprar').empty();

            var table = $('<table>').addClass('table').css('background-color', '#EBEDEF');
            var headerRow = $('<tr>');
            headerRow.append($('<th>').text('ID Producto'));
            headerRow.append($('<th>').text('Marca'));
            headerRow.append($('<th>').text('Modelo'));
            headerRow.append($('<th>').text('Stock'));
            headerRow.append($('<th>').text('Precio Unidad'));
            headerRow.append($('<th>').text('Cliente'));
            headerRow.append($('<th>').text('Cantidad'));

            table.append(headerRow);

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

                    var botonAgregar = $('<button>').addClass('btn btn-outline-danger').text('Agregar al carrito').click(function() {
                        var clienteID = clienteSelect.val();
                        var cantidad = cantidades.val();
                        agregarAlCarrito(producto, clienteID, cantidad);
                    });
                    
                    row.append($('<td>').append(botonAgregar));
                    table.append(row);
                });
            });
          
            $('#comprar').append(table);

            // var btnCambiarPrecio = $('<button>').attr('id', 'btnCambiarPrecio').addClass('btn btn-outline-primary').text('Cambiar Precio');
            // $('#comprar').append(btnCambiarPrecio);
        
            // $('#btnCambiarPrecio').click(function() {
            //     window.location.href = '/Proyecto/view/actualizar_precio.php';
            // });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener productos:', error);
        }
    });
}


function actualizarPrecio() {
    $('#formActualizarPrecio').submit(function(event) { 
        event.preventDefault(); 

        var productoID = $('#productoID').val();
        var nuevo_precio = $('#nuevo_precio').val();
        var datos = {
            productoID: productoID,
            precioUnidad: nuevo_precio
        };

        $.ajax({
            url: 'http://localhost/Proyecto/connect/api.php/producto',
            type: 'PUT', 
            dataType: 'json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response.hasOwnProperty('error')) {
                    console.log('Error al actualizar el precio del producto: ' + response.error);
                } else {
                    alert('Precio actualizado exitosamente: ' + response.mensaje);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al actualizar el precio del producto:', error);
                alert('Error al actualizar el precio del producto. Consulta la consola para más detalles sobre el error.');
            }
        });
    });
}


$(document).ready(function() {
    actualizarPrecio();
});
$('#btnMenu').click(function() {
    window.location.href = '/Proyecto/view/menu.php'; 
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
        data: JSON.stringify(datos), 
        success: function(response) {
            console.log('Respuesta de la API:', response);
            alert('Producto agregado al carrito exitosamente');
        },
        error: function(xhr, status, error) {
            alert('No es posible agregar el producto al carrito');
        }
    });
}


function obtenerCarrito() {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/carrito',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#carrito').empty();

            var filtroId = $('<input>').attr('type', 'text').attr('id', 'idFiltroCarrito').addClass('form-control').attr('placeholder', 'Filtrar Carrito por su ID');
            var botonFiltrar = $('<button>').addClass('btn btn-outline-primary').text('Buscar').click(function() {
                var idFiltro = $('#idFiltroCarrito').val();
                filtrarCarritoPorId(idFiltro);
            });

            var botonEliminarSeleccionados = $('<button>').addClass('btn btn-outline-danger').text('Eliminar seleccionados').click(function() {
                var productosSeleccionados = $('input[name="seleccionar"]:checked').map(function() {
                    return $(this).val();
                }).get();

                console.log('Productos seleccionados:', productosSeleccionados);

                eliminarProductosSeleccionados(productosSeleccionados);
            });

            $('#carrito').append(
                $('<div>').addClass('form-group row').append(
                    $('<div>').addClass('col').append(
                        filtroId
                    ),
                    $('<div>').addClass('col-auto').append(
                        botonFiltrar
                    ),
                    $('<div>').addClass('col-auto').append(
                        botonEliminarSeleccionados
                    )
                ),
                $('<br>')
            );

            var tabla = $('<table>').addClass('table').css('background-color', '#EBEDEF');
            var cabecera = $('<thead>').append(
                $('<tr>').append(
                    $('<th>').text('ID'),
                    $('<th>').text('Cliente'),
                    $('<th>').text('Marca'),
                    $('<th>').text('Modelo'),
                    $('<th>').text('Cantidad'),
                    $('<th>').text('Precio Unidad'),
                    $('<th>').text('Detalles'),
                    $('<th>').text('Acciones')
                )
            );
            tabla.append(cabecera);

            var cuerpo = $('<tbody>');
            response.forEach(function(item) {
                var row = $('<tr>').append(
                    $('<td>').text(item.id),
                    $('<td>').text(item.cliente),
                    $('<td>').text(item.nombre_marca),
                    $('<td>').text(item.nombre_modelo),
                    $('<td>').text(item.cantidad),
                    $('<td>').text(item.precioUnidad + ' €')
                );

                var btnDetalles = $('<button>').addClass('btn btn-outline-success').text('...').click(function() {
                    obtenerDetallesCarrito(item.id); 
                });
                row.append($('<td>').append(btnDetalles)); 

                var checkbox = $('<input>').attr('type', 'checkbox').attr('name', 'seleccionar').val(item.id).addClass('custom-checkbox');
                row.append($('<td>').append(checkbox));

                cuerpo.append(row);
            });

            tabla.append(cuerpo);
            $('#carrito').append(tabla);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener el carrito:', error);
            $('#carrito').text('Error al obtener el carrito.');
        }
    });
}


function filtrarCarritoPorId(carritoID) {
    var filas = $('#carrito tbody tr');
    filas.hide();
    filas.each(function() {
        var id = $(this).find('td:first').text();
        if (id === carritoID) {
            $(this).show();
        }
    });
}


function obtenerDetallesCarrito(carritoID) {
    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/carrito/detalles/' + carritoID,
        type: 'GET',
        dataType: 'json',
        success: function(item) {
            var subtotal = item.cantidad * item.precioUnidad;

            var mensaje = "DETALLES DEL CARRITO:\n";
            mensaje += "\n" + "ID CARRITO: " + item.id + "\n";
            mensaje += "NOMBRE CLIENTE: " + item.cliente + "\n";
            mensaje += "MARCA: " + item.nombre_marca + "\n";
            mensaje += "MODELO: " + item.nombre_modelo + "\n";
            mensaje += "CANTIDAD: " + item.cantidad + "\n";
            mensaje += "PRECIO UNIDAD: " + item.precioUnidad + "\n";
            mensaje += "\n" + "SUBTOTAL: " + subtotal + "\n"; 

            alert(mensaje);
            console.log(mensaje);
        },
        error: function(xhr, status, error) {
            alert('Error al obtener los detalles del carrito. Por favor, inténtalo de nuevo.');
        }
    });
}


function eliminarProductosSeleccionados(productosSeleccionados) {
    var data = {
        productosIDs: productosSeleccionados
    };

    $.ajax({
        url: 'http://localhost/Proyecto/connect/api.php/carrito',
        type: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            console.log(response);
            alert('Se ha borrado el carrito seleccionado/s con éxito!')
            obtenerCarrito();
        },
        error: function(xhr, status, error) {
            console.error('Error al eliminar productos del carrito:', error);
        }
    });
}

//TODO: Dudas aqui 
// function eliminarDelCarrito(carritoID) {
//     $.ajax({
//         url: 'http://localhost/Proyecto/connect/api.php/carrito',
//         type: 'DELETE',
//         dataType: 'json',
//         contentType: 'application/json',
//         data: JSON.stringify({ id: carritoID }), 
//         success: function(response) {
//             console.log('Producto eliminado del carrito:', response);
//             alert('Producto eliminado del carrito exitosamente');
//             obtenerCarrito();
//         },
//         error: function(xhr, status, error) {
//             console.error('Error al eliminar producto del carrito:', error);
//         }
//     });
// }





















