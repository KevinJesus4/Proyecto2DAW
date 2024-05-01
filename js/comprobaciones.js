/*VALIDA USUARIO*/
function validaFormulario() {
    var usuario = document.getElementById("usuario").value;
    var clave = document.getElementById("clave").value;

    //Comprobamos si los campos del login se encuentran vacíos
    if (usuario.trim() === "") { 
        alert("Por favor, ingresa un usuario.");
        return false;
    }
    if (clave.trim() === "") { 
        alert("Por favor, ingresa una contraseña.");
        return false;
    }
    return true;
}


/*VALIDAR MODELO*/
function validarNombreModelo(nombreModelo) {
    if (nombreModelo.trim() === '') {
        alert('El nombre del modelo no puede estar vacío');
        return false;
    }
    var regex = /^[a-zA-Z0-9\s]+$/;
    if (!regex.test(nombreModelo)) {
        alert('El nombre del modelo solo puede contener letras, números y espacios');
        return false;
    }
    return true;
}

function enviarFormularioAgregarModelo() {
    var nombreModelo = document.getElementById('nombre_modelo').value; 
    
    if (!validarNombreModelo(nombreModelo)) {
        return false;
    }
    return true;
}


/*VALIDAR PRODUCTO*/
function validarCampoProducto(campo) {
    if (campo.trim() === '') {
        alert('El campo no puede estar vacío');
        return false;
    }
    
    var regex = /^[0-9]+$/;
    if (!regex.test(campo)) {
        alert('El campo solo puede contener números');
        return false;
    }
    return true;
}

function enviarFormularioAgregarProducto() {
    var productoID  = document.getElementById('productoID').value;
    var stock = document.getElementById('stock').value;
    var precioUnidad = document.getElementById('precioUnidad').value;
    
    if (!validarCampoProducto(stock) || !validarCampoProducto(precioUnidad)) {
        return false;
    }
    return true;
}


/*VALIDAR CLIENTE*/
function validarNombreCliente(nombreCliente) {
    if (nombreCliente.trim() === '') {
        alert('El nombre del cliente no puede estar vacío');
        return false;
    }

    var regex = /^[a-zA-Z\s]+$/;
    if (!regex.test(nombreCliente)) {
        alert('El nombre del cliente solo puede contener letras y espacios');
        return false;
    }
    return true;
}

function validarFormatoEmail(email) {
   
    var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!regex.test(email)) {
        alert('El formato del correo electrónico es inválido');
        return false;
    }
    return true;
}

function enviarFormularioRegistroCliente() {
    var nombreCli = document.getElementById('nombreCli').value;
    var apellido = document.getElementById('apellido').value;
    var emailCli = document.getElementById('emailCli').value;
    
    if (!validarNombreCliente(nombreCli) || !validarNombreCliente(apellido) || !validarFormatoEmail(emailCli)) {
        return false;
    }
    return true;
}







