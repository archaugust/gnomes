var geocoder;
var map;
var markers = [];

function initialize() {
    lat = $('#ad_lat').val();
    lng = $('#ad_lng').val();
    if (lat == '')
        lat = 14.5995124;
    if (lng == '')
        lng = 120.9842195;
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(lat, lng);
    var mapOptions = {
        zoom: 14,
        center: latlng
    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var marker = new google.maps.Marker({
        draggable: true,
        icon: '/images/marker.png',
        position: latlng,
        map: map
    });
    markers.push(marker);
    google.maps.event.addListener(marker, 'dragend', function (event) {
        $("#ad_lat").val(this.getPosition().lat());
        $("#ad_lng").val(this.getPosition().lng());
    });
}

function codeAddress(address) {
    deleteMarkers();
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                draggable: true,
                icon: '/images/marker.png',
                position: results[0].geometry.location
            });
            markers.push(marker);

            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();

            $('#ad_lat').val(lat);
            $('#ad_lng').val(lng);

            google.maps.event.addListener(marker, 'dragend', function (event) {
                $("#ad_lat").val(this.getPosition().lat());
                $("#ad_lng").val(this.getPosition().lng());
            });
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function setAllMap(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

function clearMarkers() {
    setAllMap(null);
}

function deleteMarkers() {
    clearMarkers();
    markers = [];
}

$('#ad_ad_type').change(function () {
    var listing_type = $('#ad_ad_type').val();
    var selected = $('#cat_selected').val();
    $('#categoryDiv').slideUp(500).html('<i class="fa fa-refresh fa-spin fa-2x"></i><span class="sr-only">Loading...</span>').slideDown(500);
    $.ajax({
        url: "/categories",
        data: 'listing_type='+ listing_type + '&selected='+ selected + '&mode=1',
        type: "POST",
        success: function (data) {
            $('#categoryDiv').fadeOut(0).html(data).slideDown(500);
        }
    });
});

$('#ad_province').change(function () {
    var province = $('#ad_province').val();
    var selected = $('#ad_city_id').val();
    $('#cityDiv').slideUp(500).html('<i class="fa fa-refresh fa-spin fa-2x"></i><span class="sr-only">Loading...</span>').slideDown(500);
    $.ajax({
        url: "/cities",
        data: 'province='+ province + '&selected='+ selected +'&field_name=ad',
        type: "POST",
        success: function (data) {
            $('#cityDiv').fadeOut(0).html(data).slideDown(500);
        }
    })

    $('#address').val($('#ad_province option:selected').text());
    codeAddress($('#address').val());
});

var max_fields      = 10; //maximum input boxes allowed
var wrapper         = $("#images"); //Fields wrapper
var add_button      = $(".add_field_button"); //Add button ID

var x = 1; //initlal text box count
$(add_button).click(function(e){ //on add input button click
    e.preventDefault();
    if(x < max_fields){ //max input box allowed
        x++; //text box increment
        $(wrapper).append('<div class="clearfix"><input type="file" name="image[]" class="pull-left" /><a href="#" class="remove_field btn btn-sm btn-default">Remove</a></div>'); //add input box
    }
});

$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    e.preventDefault(); $(this).parent('div').remove(); x--;
});