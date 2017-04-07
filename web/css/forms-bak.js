$('#first-yes').click(function(){
    $(this).addClass('active');
    $('#new').val('Yes');
    $('#first-no').removeClass('active');
    $('.form-details').addClass('in', 300, "linear");
    $('.form-details-first').addClass('in', 300, "linear");
    $('#name_first').focus();
});
$('#first-no').click(function(){
    $(this).addClass('active');
    $('#new').val('No');
    $('#first-yes').removeClass('active');
    $('.form-details').addClass('in', 300, "linear");
    $('.form-details-first').removeClass('in', 300, "linear");
    $('#name_first').focus();
});
$('div#form-contact input').click(function () {
    $('.form-appointment').addClass('in', 1000, "linear");
});
$('div#appointment-type input').click(function () {
    $("#part-1").focus().scrollToMe();
});
$('#appointment-check').click(function () {
    $('.form-appointment-other').removeClass('in', 300, "linear");
    $('.form-referral').removeClass('in', 300, "linear");
    $('.form-dentist').addClass('in', 300, "linear");
    $("#part-1").focus().scrollToMe();
});
$('#appointment-consult').click(function () {
    $('.form-appointment-other').removeClass('in', 300, "linear");
    $('.form-referral').addClass('in', 300, "linear");
    $("#part-1").focus().scrollToMe();
});
$('#appointment-other').click(function () {
    $('.form-appointment-other').addClass('in', 300, "linear");
    $('.form-referral').addClass('in', 300, "linear");
    $('#form-appointment-other').focus();
});

$('#referral-yes').click(function () {
    $('.form-referral-yes').addClass('in', 300, "linear");
    $('.form-referral-no').removeClass('in', 300, "linear");
    $('.form-dentist').addClass('in', 1000, "linear");
});
$('#referral-no').click(function () {
    $('.form-referral-tcds').removeClass('in', 300, "linear");
    $('.form-referral-yes').removeClass('in', 300, "linear");
    $('.form-referral-no').addClass('in', 300, "linear");
    $('.form-dentist').addClass('in', 1000, "linear");
});

$('#referred-by-tcds').click(function () {
    $('.form-referral-tcds').addClass('in', 300, "linear");
    $('.form-dentist').addClass('in', 1000, "linear");
});
$('#referred-by-other').click(function () {
    $('.form-referral-tcds').removeClass('in', 300, "linear");
    $('.form-dentist').addClass('in', 1000, "linear");
});
$('div#dentist input').click(function(){
    $('.form-dentist-schedule').addClass('in');
    var dentist = $(this).val();
    $('div#schedule-days div').removeClass('hidden');
    switch (dentist) {
        case 'Dr Joanna Pedlow':
            $('#wed').addClass('hidden');
            $('#thu').addClass('hidden');
            break;
        case 'Dr Ellen Fei':
            $('#tue').addClass('hidden');
            $('#thu').addClass('hidden');
            $('#fri').addClass('hidden');
            break;
    }
    $("#part-2").scrollToMe();
});
$('div#schedule-time input').click(function() {
    $('#form-end').removeClass('hidden', 300, "linear");
});
