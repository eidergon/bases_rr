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

btn.click(function () {
    $.ajax({
        url: "view/cargar_base.html",
        success: function (result) {
            $("main").html(result);
            $('#loader').addClass('hidden');
        }
    });
});

a.click(function (e) {
    e.preventDefault();

    $.ajax({
        url: "view/descargar_base.html",
        success: function (result) {
            $("main").html(result);
            $('#loader').addClass('hidden');
        }
    });
});

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
            if (response.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "",
                    text: response.message,
                    footer: "Total: " + response.total_records + " insertados: " + response.inserted_records + " existente: " + response.duplicados,
                });
                $("#archivos").val("");
                $('#loader').addClass('hidden');
            } else if (response.status === "error") {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message,
                    footer: "Total: " + response.total_records + " insertados: " + response.inserted_records + " existente: " + response.duplicados,
                });
                $("#archivos").val("");
                $('#loader').addClass('hidden');
            }
        },
    });
});