
$('.personDeAssociatedDiv').click(function() {

    let profilId = $(this).attr('id').split('-')[1];
    $('#disclaimer-'+profilId).show();
})


$('.goOnButton').click(function() {
    let profilId = $(this).attr('id').split('-')[1];

    let idConnected = $('#connectedId').val();
   
    let url = `person/unassociate/${idConnected}/${profilId}`;

    $.ajax({
        type: "POST",
        url: urlRequest,
        data: { url, type: "DELETE" },
        dataType: "json",
        beforeSend() {},
        success(json) {
           // $('#personIdDiv-'+profilId).fadeOut(300);
            $('#personIdDiv-'+profilId)
            .addClass("animated bounceOutUp")
            .delay(750)
            .hide(0);
        }
    });




})

$('.cancelledButton').click(function() {
    let profilId = $(this).attr('id').split('-')[1];
    $('#disclaimer-'+profilId).hide();

})