$('#our-products').addClass('active');

$('#type_id').change(function () {
    var type_id = $("#type_id").val();
    if (type_id)
    {
        var stock = type_id.split(":");
        var stock = stock[1];
        if (stock == 0)
            submit = '<input type="submit" name="submit" disabled value="Out of Stock" class="btn btn-primary" />';
        else
            submit = '<input type="submit" name="submit" value="Add to Cart" class="btn btn-primary" />';

        $('#submitDiv').html(submit);
    }
});

$('#description').readmore({
    speed: 75,
    collapsedHeight: 200,
    moreLink: '<a href="javascript:void(0)"><i class="fa fa-plus-square fa-fw"></i> Read more</a>',
    lessLink: '<a href="javascript:void(0)"><i class="fa fa-minus-square fa-fw"></i> Close</a>'
});
$('#images').carousel({
    interval: 3000
}).on('slide.bs.carousel', function (e) {
    var nextH = $(e.relatedTarget).height();
    $(this).find('.active.item').parent().animate({ height: nextH }, 500);
});
