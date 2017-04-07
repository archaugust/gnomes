$(window).load(function() {
    $('#ordering').change(function () {
        $('#formOrdering').submit();
    });

    $('#filter_provinces').change(function () {
        var province = $('#filter_provinces').val();
        $('#filter_citiesDiv').slideUp(100).html('<i class="fa fa-refresh fa-spin fa-2x"></i><span class="sr-only">Loading...</span>').slideDown(500);
        $.ajax({
            url: "/cities",
            data: 'province='+ province,
            type: "POST",
            success: function (data) {
                $('#filter_citiesDiv').fadeOut(0).html(data).slideDown(500);
            }
        })
    });
});
