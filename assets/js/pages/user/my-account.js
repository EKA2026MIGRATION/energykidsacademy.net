document.getElementById("changePassWord").addEventListener(
    "submit",
    event => {
        event.preventDefault();

        let form = $("#changePassWord");
        let password = $("[name=new_password]").val();     
        let data = JSON.stringify({ plainPassword: password });
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            contentType: "application/json",
            headers: {
                'Authorization':'Bearer ' + tokenAuth
            },      
            contentLength: data.length,
            crossDomain: true,
            dataType: "json",
            data,
            beforeSend() {
                $("#changePassWord [type=submit]")
                    .attr("disabled", true);
            },
            success(data) {
                $("#changePassWord [type=submit]")
                    .attr("disabled", false);

                    toastr.success('Votre mot de passe a été modifié.');
            
         
            },
            error(data) {
                $("#changePassWord [type=submit]")
                    .attr("disabled", false);
            }
        }); 
    },
    false
);

document.getElementById("changeEmail").addEventListener(
    "submit",
    event => {
        event.preventDefault();

        let form = $("#changeEmail");
        let email = $("[name=new_email]").val();    
        let data = JSON.stringify({ email });
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            contentType: "application/json",
            headers: {
                'Authorization':'Bearer ' + tokenAuth
            },      
            contentLength: data.length,
            crossDomain: true,
            dataType: "json",
            data,
            beforeSend() {
                $("#changeEmail [type=submit]")
                    .attr("disabled", true);
            },
            success(data) {
                $("#changeEmail [type=submit]")
                    .attr("disabled", false);

                    toastr.success('Votre email a été modifié.');
            
         
            },
            error(data) {
                $("#changeEmail [type=submit]")
                    .attr("disabled", false);
            }
        }); 
    },
    false
);
