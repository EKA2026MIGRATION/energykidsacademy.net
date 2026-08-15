dropContainer.ondragover = dropContainer.ondragenter = evt => {
    evt.preventDefault();
};

dropContainer.ondrop = evt => {
    fileInput.files = evt.dataTransfer.files;
    evt.preventDefault();
};

const previewOnDiv = () => {
    const file = document.querySelector("#fileInput").files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        const image = document.getElementById("photoRender");
        const strImage = reader.result.replace(/^data:image\/[a-z]+;base64,/, "");
        image.src = `data:image/jpeg;base64,${strImage}`;

        $("#photoRender").fadeIn();

        const imageCompressor = new ImageCompressor();

        const compressorSettings = {
            toWidth: 400,
            toHeight: 400,
            mimeType: "image/png",
            mode: "strict",
            quality: 0.6,
            speed: "low"
        };

        imageCompressor.run(image.src, compressorSettings, proceedCompressedImage);
    };
};

function proceedCompressedImage(compressedSrc) {
    $.ajax({
        type: "POST",
        url: urlPhoto,
        data: { base64: compressedSrc, folder: "person" },
        dataType: "json",
        beforeSend() {
            $(".loading").show();
        },
        success(json) {
            $(".loading").hide();
            $("#photoUrl").val(json.url);
        }
    });
}

document.getElementById("personForm").addEventListener(
    "submit",
    event => {
        event.preventDefault();
        let form = $("#personForm");
        let url = form.attr("action");
        let type = "POST";
        let personId = $("#personId").val();
        let relationText = $("#relationText").val();
        let email = $('#email').val();
        let data = $(form).serializeToJSON();
        const relations = [];

        if(email) {
            console.log("create user");
            email = email.toLowerCase();
            createUser(email);

            /****** AJOUTER LA RELATION  *******/
        } 




        if (url.includes("modify")) {
            type = "PUT";
        }
        else
        {
            relations[0] = {related: personId, relation:relationText};
        }

        $.ajax({
            type: "POST",
            url: urlRequest,
            data: { type, url, data, relations},
            dataType: "json",
            beforeSend() {
                $("#personForm [type=submit]")
                    .attr("disabled", true)
                    .attr("value", "Envoi en cours..");
            },
            success(json) {
                $("#personForm [type=submit]")
                    .attr("disabled", false)
                    .attr("value", "Envoyer");
                $("#personForm")[0].reset();

                if (json.status == true) {
                    if (url.includes("modify")) {

                        swal({
                            title: "Profil modifié !",
                            text: "Le profil a bien été modifié.",
                            type: "success",
                            confirmButtonText: "Afficher le profil",
                            cancelButtonText: "Fermer",
                            showCancelButton: true
                        }).then(result => {
                            if (result.value) {
                                location.href = `${urlHost}utilisateur/profil/id/${json.person.personId}/`;
                            }
                        });

                    }
                    else
                    {
                        //
                    }
                } else {
                    swal({
                        title: "Erreur",
                        text: "Une erreur est survenue.",
                        type: "warning"
                    });
                }
            }
        });
        

    },
    false
);

function createUser(email) {

    let plainPassword = generatePassword();
    let data = JSON.stringify({ email: email, plainPassword, apiKey: sha1(email + 'LKf7*D') });

    $.ajax({
        url: "https://api.appli-v.net/user/api/create",
        type: "post",
        data: {email: email, plainPassword, apiKey: sha1(email + 'LKf7*D')},
        crossDomain: true,
        dataType: "json",
        beforeSend() {
            //
        },
        success(json) {
          if(json.allowUse == true)
          {

                console.log('create person');
                
                let firstname = $("#firstname").val();
                let lastname = $("#last_name").val();
                let identifier = json.identifier;
                let data = {firstname, lastname, identifier, key: sha1(firstname)}; 
                $.ajax({
                    type: "POST",
                    url: urlRequest,
                    data: {type:'POST', url:'person/create', data},
                    dataType: "json",
                    beforeSend() {},
                    success(json2) {

                        console.log(json2);
                        swal({
                            title: "Profil créé",
                            text: "Un email a été envoyé avec les identifiants de connexion",
                            type: "success",
                            confirmButtonText: "Associer les enfants",
                        }).then(result => {
                            if (result.value) {
                                location.href = `${urlHost}utilisateur/associer-les-enfants/i/`+json2.person.personId+'/';
                            }
                        });

                    }
                });
          }
          else
          {
            toastr.error('Au moins un des champs est invalide!');
          }

        },
        error(data) {
                toastr.error('Une erreur est survenue');    
        }
    });


}


function generatePassword() {
    var length = 8,
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
}
