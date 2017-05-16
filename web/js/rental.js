$('.button').click(function(){
	$(this).parent('.o-price').find('.details').slideToggle();
});

var $guestHolder;

var $addGuestLink = $('<a href="#" class="add_item_link"></a>');
var $newGuestLi = $('<div class="o-guest"></div>').append($addGuestLink);

$(function() {
	$('#arch_rental_booking_collect_date').datepicker({
	    dateFormat: 'yy-mm-dd',
	    changeMonth: true, 
	    changeYear: true, 
    });
	$('#arch_rental_booking_return_date').datepicker({
	    dateFormat: 'yy-mm-dd',
	    changeMonth: true, 
	    changeYear: true, 
    });
	
    $guestHolder = $('div#guests');

    $guestHolder.append($newGuestLi);

    $guestHolder.data('index', $guestHolder.find(':input').length);

    $addGuestLink.on('click', function(e) {
        e.preventDefault();

        addItemForm($guestHolder, $newGuestLi);
    });
});

function blueAsterisk() {
	$('label').each(function(){
		if ($(this).find('span').length == 0)
			$(this).html($(this).html().replace('*', '<span class="u-blue">*</span>'));
	})
}

function setGuestNumbers() {
	var ctr = 1;
	$('.guest_no').each(function(){
		$(this).html(Math.floor(ctr));
		ctr += .5;
	});
}

$('#guests').change(function(){
    var guests = $(this).val(),
    	fields = $guestHolder.data('index'),
    	prototype = $guestHolder.data('prototype'),
		    	index,
		    	ctr;

	if (guests < fields) {
    	for(i=fields; i>guests; i--) {
    		$('.delete').last().trigger('click');
    		index = $guestHolder.data('index');
    		$guestHolder.data('index', index - 1);
	    }
    }
    
    if (guests > fields) {
    	ctr = guests - fields;
    	for(i=0; i<ctr; i++) {
    		addItemForm($guestHolder, $newGuestLi);
    		setGuestNumbers();
	    }
	}
});

function addItemForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);
    
    var $newFormLi = $('<div class="o-guest"></div>').append(newForm);
    $newLinkLi.before($newFormLi);

    addItemFormDeleteLink($newFormLi);
    blueAsterisk();
}

function addItemFormDeleteLink($itemFormLi) {
    var $removeFormA = $('<a href="#" class="delete"></a>');
    $itemFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $itemFormLi.remove();
    });
}