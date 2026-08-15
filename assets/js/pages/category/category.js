$('.titleCategory').click(function() {
    let categorykey = $(this).data('categorykey');
     $('html, body').animate({
        scrollTop: $('#category'+categorykey).offset().top
    }, 500, 'linear');
})


let pid = $('#pidInput').val();

if(pid != null) {
    $('html, body').animate({
        scrollTop: $('#product-'+pid).offset().top
    }, 500, 'linear');
}





