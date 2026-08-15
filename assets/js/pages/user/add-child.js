$(() => {


    $("#birthday").datepicker({
        altField: "#datepicker",
        altFormat: "yy-mm-dd",
        closeText: "Fermer",
        prevText: "Précédent",
        nextText: "Suivant",
        currentText: "Aujourd'hui",
        monthNames: [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        ],
        monthNamesShort: [
            "Janv.",
            "Févr.",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juil.",
            "Août",
            "Sept.",
            "Oct.",
            "Nov.",
            "Déc."
        ],
        dayNames: [
            "Dimanche",
            "Lundi",
            "Mardi",
            "Mercredi",
            "Jeudi",
            "Vendredi",
            "Samedi"
        ],
        dayNamesShort: ["Dim.", "Lun.", "Mar.", "Mer.", "Jeu.", "Ven.", "Sam."],
        dayNamesMin: ["D", "L", "M", "M", "J", "V", "S"],
        weekHeader: "Sem.",
        dateFormat: "dd/mm/yy",
        changeYear: true,
        firstDay: 1,
        yearRange: "-100:+0",
        maxDate: new Date()
    });


});

const toogleMedical = () =>
{
    $(".medical").toggle();
}



$(() => {


    let autocomplete;
    let geocoder;
    const input = document.getElementById("autocomplete");
      const options = {
        componentRestrictions: { country: "fr" }
      };


    autocomplete = new google.maps.places.Autocomplete(input, options);

    google.maps.event.addListener(autocomplete, "place_changed", () => {
        const place = autocomplete.getPlace();

        for (let i = 0; i < place.address_components.length; i++) {
            for (let j = 0; j < place.address_components[i].types.length; j++) {
                if (place.address_components[i].types[j] == "postal_code") {

                    var postal = place.address_components[i].long_name;
                }

                if (place.address_components[i].types[j] == "street_number") {

                    var street_number = place.address_components[i].long_name;
                }

                if (place.address_components[i].types[j] == "route") {

                    var route = place.address_components[i].long_name;
                }

                if (place.address_components[i].types[j] == "locality") {

                    var town = place.address_components[i].long_name;
                }

                if (place.address_components[i].types[j] == "country") {

                    var country = place.address_components[i].long_name;
                }

            }
        }

        let name = place.name;
        let address = street_number + ' ' + route;
        let latitude = place.geometry.location.lat();
        let longitude = place.geometry.location.lng();

        let googlePlaceId = place.place_id;
        let photo = place.photos[0].getUrl();

        let data = {name, address, postal, town, country, latitude, longitude, googlePlaceId, photo};

        let url = "school/create";
        let type = "POST";
        $.ajax({
          type: "POST",
          url: urlRequest,
          data: {type, url, data},
          dataType: "json",
          beforeSend() {

          },
          success(json) {

            $("#school").val(json.school.schoolId);

          }
        });


    });



});


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
        data: { base64: compressedSrc, folder: "child" },
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

const changeRelation = data =>
{
    var relation = prompt("Indiquez la relation (Père, mère, baby-sitter, etc..)", "");
    
    if (relation != null) {
      $(data).attr('data-relation', relation);
      $(data).next('label').append(' - ' + relation);
    }

}

document.getElementById("childForm").addEventListener(
    "submit",
    event => {
        event.preventDefault();
        let form = $("#childForm");
        let url = form.attr("action");
        const dataRelation = [];
        let personId = $("#personId").val();

        dataRelation[0] = {personId, relation:''};

        let i = 1;
        $(".choiceProduct")
          .find(":checkbox:checked")
          .each(function() {
           
            let idPerson = $(this).attr('data-person');
            let relationData = $(this).attr('data-relation');

            dataRelation[i] = { personId: idPerson, relation: relationData };
                i++;

            });

            

        let data = $(form).serializeToJSON();
        let type = "POST";

        if (url.includes("modify")) {
            type = "PUT";
        }

        $.ajax({
            type: "POST",
            url: urlRequest,
            data: { url, type, data, links: dataRelation },
            dataType: "json",
            beforeSend() {
                $(".loading").show();
            },
            success(json) {
                $(".loading").hide();

                if (json.status == true) {
                    swal({
                        title: "Confirmation",
                        text: json.message,
                        type: "success",
                        confirmButtonText: "Afficher le profil",
                        cancelButtonText: "Fermer",
                        showCancelButton: true
                    }).then(result => {
                        if (result.value) {
                            location.href = `${urlHost}enfant/profil/id/${json.child.childId}/`;
                        }
                    });
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

