document.getElementById("lostPassWordFormConfirm").addEventListener(
    "submit",
    event => {
        event.preventDefault();

        let form = $("#lostPassWordFormConfirm");
        let password = $("[name=new_password]").val();
        let token = $("#token").val();        
        let data = JSON.stringify({ plainPassword: password });
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            contentType: "application/json",
            contentLength: data.length,
            crossDomain: true,
            dataType: "json",
            data,
            beforeSend() {
                $("#lostPassWordFormConfirm [type=submit]")
                    .attr("disabled", true);
            },
            success(data) {
                $("#lostPassWordFormConfirm [type=submit]")
                    .attr("disabled", false)
                    .attr("value", "Retrouver mon compte");
                    toastr.success('Votre mot de passe a été modifié. Vous pouvez vous connecter.');
                    $('#lostPassWordFormConfirm').hide();
                    $('#showLogButton').show();
            },
            error(data) {
                $("#lostPassWordFormConfirm [type=submit]")
                    .attr("disabled", false)
                    .attr("value", "Retrouver mon compte");
                    
            }
        }); 
    },
    false
);
