
    $('.childItem').click(function() {
        console.log('touch');
        let childId = $(this).data('childid');

        let currentChildIdValue = $('#inputChildId'+childId).val();

        if(currentChildIdValue == "0") {
            $('#divChildId'+childId).css('background-color', 'darkblue');
            $('#inputChildId'+childId).val(childId);

        } else {
            $('#divChildId'+childId).css('background-color', 'lightgrey');
            $('#inputChildId'+childId).val("0");
        }



        console.log(currentChildIdValue);

    })