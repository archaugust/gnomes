$(function() {

	var $collectionHolder;

	var $addAttachmentLink = $('<a href="#" class="add_attachment_link btn btn-default">Add Attachment</a>');
	var $newLinkLi = $('<div></div>').append($addAttachmentLink);

	jQuery(document).ready(function() {
	    $collectionHolder = $('div.attachments');

	    $collectionHolder.find('div').each(function() {
	        addAttachmentFormDeleteLink($(this));
	    });

	    $collectionHolder.append($newLinkLi);

	    $collectionHolder.data('index', $collectionHolder.find(':input').length);

	    $addAttachmentLink.on('click', function(e) {
	        e.preventDefault();

	        addAttachmentForm($collectionHolder, $newLinkLi);
	    });
	});

	function addAttachmentForm($collectionHolder, $newLinkLi) {
	    var prototype = $collectionHolder.data('prototype');

	    var index = $collectionHolder.data('index');

	    var newForm = prototype.replace(/__name__/g, index);

	    $collectionHolder.data('index', index + 1);

	    var $newFormLi = $('<div class="row"></div>').append('<div class="col-md-9">'+ newForm +'</div>');
	    $newLinkLi.before($newFormLi);

	    addAttachmentFormDeleteLink($newFormLi);
	}

	function addAttachmentFormDeleteLink($attachmentFormLi) {
	    var $removeFormA = $('<div class="col-md-3"><a href="#" class="btn btn-default btn-sm">Remove</a></div>');
	    $attachmentFormLi.append($removeFormA);

	    $removeFormA.on('click', function(e) {
	        // prevent the link from creating a "#" on the URL
	        e.preventDefault();

	        // remove the li for the tag form
	        $attachmentFormLi.remove();
	    });
	}
	
	$( ".datepicker" ).datepicker({
	    changeMonth: true,
	    changeYear: true,
	    yearRange: "-40:+0",
	    dateFormat: 'd MM, yy',
	});
	
	$('#form_referral').submit(function(event) {
		var data = new FormData($(this)[0]);
	    $('#referDiv').html('<div class="col-md-12"><i class="fa fa-refresh fa-spin fa-5x red"></i><span class="sr-only">Loading...</span></div>');
	    $('body,html').animate({
	        scrollTop : $('#referDiv').offset().top - 400
	    }, 500);
	    $.ajax({
	        url: "/referral",
	        data: data,
	        type: "POST",
	        async: false,
	        cache: false,
            processData: false,
            contentType: false,
	        success: function (data) {
	            $('#referDiv').html(data);
	        }
	    })
	    event.preventDefault();
	});

});
