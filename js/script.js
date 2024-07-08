const cloud = $("#cloud");
const barraLateral = $(".barra-lateral");
const spans = $("span");
const palanca = $(".switch");
const circulo = $(".circulo");
const menu = $(".menu");
const main = $("main");
const a = $("a");
const btn = $("button");

menu.on("click", function () {
    barraLateral.toggleClass("max-barra-lateral");
    if (barraLateral.hasClass("max-barra-lateral")) {
        menu.children().eq(0).hide();
        menu.children().eq(1).show();
    } else {
        menu.children().eq(0).show();
        menu.children().eq(1).hide();
    }
    if ($(window).width() <= 320) {
        barraLateral.addClass("mini-barra-lateral");
        main.addClass("min-main");
        spans.each(function () {
            $(this).addClass("oculto");
        });
    }
});

palanca.on("click", function () {
    let body = $("body");
    body.toggleClass("dark-mode");
    body.toggleClass("");
    circulo.toggleClass("prendido");
});

cloud.on("click", function () {
    barraLateral.toggleClass("mini-barra-lateral");
    main.toggleClass("min-main");
    spans.each(function () {
        $(this).toggleClass("oculto");
    });
});

// funcion para llamar la visual del cargue 
btn.click(function () {
    $.ajax({
        url: "view/cargar_base.html",
        success: function (result) {
            $("main").html(result);
            $('#loader').addClass('hidden');
        }
    });
});

// funcion para llamar la 
a.click(function (e) {
    e.preventDefault();
    var page = $(this).data("page");

    if (page === "vista") {
        var url = "view/vista.php";
    } else {
        var url = "view/descargar_base.html";
    }

    $.ajax({
        url: url,
        success: function (result) {
            $("main").html(result);
        }
    });
});

// funcion para subir la base
$(document).on('submit', '#cargar_base', function (e) {
    e.preventDefault();
    $('#loader').removeClass('hidden');

    var formData = new FormData(this);
    $.ajax({
        url: "php/cargar_base.php",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            var jsonResponse = JSON.parse(response);
            if (jsonResponse.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "",
                    text: jsonResponse.message
                });
                $("#archivos").val("");
                $('#loader').addClass('hidden');
            } else if (jsonResponse.status === 'error') {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message
                });
                $("#archivos").val("");
                $('#loader').addClass('hidden');
            }
        },
    });
});

// funcion para mostrar las columnas de las tablas 
$(document).on('change', '#tabla', function () {
    var tabla = $(this).val();
    var columnasDiv = $('#columnas');
    columnasDiv.empty();

    if (tabla === 'historico_cuentas') {
        columnasDiv.html(`
                <label class="label" for="columnas">Seleccione las columnas:</label>
                <label class="label">
                    <input type="checkbox" name="columnas[]" value="estado" onchange="mostrarOpciones()"> 
                    Estado
                </label>

                <label class="label">
                    <input type="checkbox" name="columnas[]" value="zona" onchange="mostrarOpciones()"> 
                    Zona
                </label>

                <label class="label">
                    <input type="checkbox" name="columnas[]" value="valor1" onchange="mostrarOpciones()"> 
                    Valor
                </label>

                <label class="label">  
                    <input type="checkbox" name="columnas[]" value="fecha_actualizacion" onchange="mostrarOpciones()"> 
                    Fecha de Actualización
                </label>

                <label class="label">  
                    <input type="checkbox" name="columnas[]" value="tipo_servicios" onchange="mostrarOpciones()"> 
                    Tipo de Servicios
                </label>

                <label class="label">  
                    <input type="checkbox" name="columnas[]" value="categoria_servicio" onchange="mostrarOpciones()"> 
                    Categoria de Servicio
                </label>
            `);
    } else if (tabla === 'numeros_detalle') {
        columnasDiv.html(`
                <label for="columnas" class="label">Seleccione las columnas:</label>
                <label class="label">
                    <input type="checkbox" name="columnas[]" value="hogar" onchange="mostrarOpciones()"> 
                    Hogar
                </label>

                <label class="label">
                    <input type="checkbox" name="columnas[]" value="estado2" onchange="mostrarOpciones()"> 
                    Estado
                </label>

                <label class="label">
                    <input type="checkbox" name="columnas[]" value="fecha_actualizacion" onchange="mostrarOpciones()"> 
                    Fecha de Actualización
                </label>
            `);
    }
});

// llama la funcion de la opciones
$(document).on('change', 'input[name="columnas[]"]', function () {
    mostrarOpciones();
});

// funcion para mostrar las opciones de busqueda
function mostrarOpciones() {
    var columnas = $('input[name="columnas[]"]:checked');
    var opciones = $('#opciones');
    opciones.empty();

    columnas.each(function () {
        var columna = $(this).val();
        if (columna == 'estado') {
            opciones.append('<label class="label">Estado: <select name="estado" class="select" required>' +
                '<option value="ACTIVO">ACTIVO</option>' +
                '<option value="CANCELADO">CANCELADO</option>' +
                '<option value="CANCELADO DEBE">CANCELADO DEBE</option>' +
                '<option value="NO RECUPERABLE">NO RECUPERABLE</option>' +
                '</select></label>');
        } else if (columna == 'zona') {
            opciones.append('<label class="label">Zona: <select name="zona" class="select" required>' +
                '<option value="DENTRO">DENTRO</option>' +
                '<option value="FUERA">FUERA</option>' +
                '<option value="SIN COBERTURA">SIN COBERTURA</option>' +
                '</select></label>');
        } else if (columna == 'valor1') {
            opciones.append('<label class="label">Operacion: <select name="operacion" id="operacion" class="select" required>' +
                '<option value="">---------</option>' +
                '<option value="menor_que">Menor que</option>' +
                '<option value="mayor_que">Mayor que</option>' +
                '<option value="entre">Entre</option>' +
                '</select></label>');
            opciones.append('<div id="dynamic-inputs"></div>');
        } else if (columna == 'fecha_actualizacion') {
            opciones.append('<label class="label">Fecha de Actualización: <input type="date" name="fecha_actualizacion" class="select" required></label>');
        } else if (columna == 'hogar') {
            opciones.append('<label class="label">Hogar: <select name="hogar" class="select" required>' +
                '<option value="SI">SI</option>' +
                '<option value="NO">NO</option>' +
                '</select></label>');
        } else if (columna == 'estado2') {
            opciones.append('<label class="label">Estado: <select name="estado" class="select" required>' +
                '<option value="ACTIVO">ACTIVO</option>' +
                '<option value="NA">NA</option>' +
                '</select></label>');
        } else if (columna == 'tipo_servicios') {
            opciones.append('<label class="label">Tipo de servicio: <select name="tipo_servicios" class="select" required>' +
                '<option value="ninguno">Ninguno</option>' +
                '<option value="simple">Simple</option>' +
                '<option value="doble">Doble</option>' +
                '<option value="triple">Triple</option>' +
                '</select></label>');
        } else if (columna == 'categoria_servicio') {
            opciones.append('<label class="label">Categoria: <select name="categoria_servicio" class="select" required>' +
                '<option value="ninguno">Ninguno</option>' +
                '<option value="internet">Internet</option>' +
                '<option value="voz">Voz</option>' +
                '<option value="tv">TV</option>' +
                '<option value="internet+tv">Internet + TV</option>' +
                '<option value="voz+internet">Voz + Internet</option>' +
                '<option value="tv+voz">TV + Voz</option>' +
                '<option value="tv+voz+internet">TV + Voz + Internet</option>' +
                '</select></label>');
        }
    });
}

$(document).on('change', '#operacion', function () {
    var selectedOption = $(this).val();
    var dynamicInputs = $('#dynamic-inputs');
    dynamicInputs.empty();

    if (selectedOption === 'menor_que' || selectedOption === 'mayor_que') {
        dynamicInputs.append('<label class="label">Valor: <input type="number" class="select" name="valor" placeholder="Valor" required></label>');
    } else if (selectedOption === 'entre') {
        dynamicInputs.append('<label class="label">Valor: <input type="number" name="valor_min" placeholder="Mínimo" class="select" required> - <input type="number" name="valor_max" placeholder="Máximo" class="select" required></label>');
    }
});

// funcion para hacer consulta de las tablas
$(document).on('submit', '#descargar_base', function (e) {
    e.preventDefault();
    $.ajax({
        url: 'php/descargar_base.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            $('#resultado').html(response);
        }
    });
});

// funcion para cambiar los valores de la tabla categoria de servicios
$(document).on('submit', '#cambiar-precios', function (e) {
    e.preventDefault();
    $.ajax({
        url: 'php/cambiar_precios.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            $('#table-cambiar-precios').html(response);
        }
    });
});

// funcion para cambiar los valores de la tabla tipo de servicios
$(document).on('submit', '#cambiar-valor', function (e) {
    e.preventDefault();
    $.ajax({
        url: 'php/cambiar_valor.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            $('#tabla-tipo-servicios').html(response);
        }
    });
});
