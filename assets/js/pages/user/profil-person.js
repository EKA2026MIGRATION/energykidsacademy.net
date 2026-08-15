
$(() => {
    let autocomplete;
    let geocoder;
    const input = document.getElementById("autocomplete");
    const options = { types: ["address"] };

    autocomplete = new google.maps.places.Autocomplete(input, options);


    google.maps.event.addListener(autocomplete, "place_changed", () => {
        const place = autocomplete.getPlace();
        console.log(place);

        for (let i = 0; i < place.address_components.length; i++) {
            for (let j = 0; j < place.address_components[i].types.length; j++) {
                if (place.address_components[i].types[j] == "postal_code") {
                    document.getElementById("postal_code").value = place.address_components[i].long_name;
                };

                if (place.address_components[i].types[j] == "country") {
                    document.getElementById("country").value = place.address_components[i].long_name;
                };

                if (place.address_components[i].types[j] == "locality") {
                    document.getElementById("locality").value = place.address_components[i].long_name;
                };
            }
        }
    });
});
/*
document
    .getElementById("deletePerson")
    .addEventListener("click", function(event) {
        const idPerson = $(this).attr("data-id-person");

        swal({
            title: "Attention",
            text: "La suppression est irréversible.",
            type: "warning",
            confirmButtonText: "Supprimer",
            cancelButtonText: "Annuler",
            showCancelButton: true
        }).then(result => {
            if (result.value) {
                deletePerson(idPerson);
            }
        });
    });*/

var deletePerson = idPerson => {
    let url = `person/delete/${idPerson}`;

    $.ajax({
        type: "POST",
        url: urlRequest,
        data: { url, type: "DELETE" },
        dataType: "json",
        beforeSend() {
            $("#deletePerson")
                .attr("disabled", true)
                .html("Suppression en cours..");
        },
        success(json) {
            if (json.status == true) {
                swal({
                    title: "Suppression",
                    text: json.message,
                    type: "success",
                    confirmButtonText: "Retour",
                    showCancelButton: false
                }).then(result => {
                    if (result.value) {
                        location.href = urlHost;
                    }
                });
            } else {
                swal({
                    title: "Suppression",
                    text: "Une erreur est survenue.",
                    type: "warning"
                });
            }
        }
    });
};

const editPhone = idPhone => {
    let url = `phone/display/${idPhone}`;
    $("#phoneForm").attr("action", `phone/modify/${idPhone}`);

    $.ajax({
        type: "POST",
        url: urlRequest,
        data: { type: "GET", url },
        dataType: "json",
        beforeSend() {
            $("#loaderFormEditPhone").show();
        },
        success(json) {
            $("#loaderFormEditPhone").hide();

            $("#phoneForm")
                .find("input")
                .each(function() {
                    const name = $(this).attr("name");
                    $(this).val(json[name]);
                });
        }
    });
};

const editAddress = idAddress => {
    let url = `address/display/${idAddress}`;
    $("#adresseForm").attr("action", `address/modify/${idAddress}`);

    $.ajax({
        type: "POST",
        url: urlRequest,
        data: { type: "GET", url },
        dataType: "json",
        beforeSend() {
            $("#loaderFormEditAddress").show();
        },
        success(json) {
            $("#loaderFormEditAddress").hide();

            $("#adresseForm")
                .find("input")
                .each(function() {
                    const name = $(this).attr("name");
                    $(this).val(json[name]);
                });
        }
    });
};

const deletePhone = idPhone => {
    swal({
        title: "Attention",
        text: "La suppression est irréversible.",
        type: "warning",
        confirmButtonText: "Supprimer",
        cancelButtonText: "Annuler",
        showCancelButton: true
    }).then(result => {
        if (result.value) {
            deletePhoneSubmit(idPhone);
        }
    });
};

const deleteAddress = idAddress => {
    swal({
        title: "Attention",
        text: "La suppression est irréversible.",
        type: "warning",
        confirmButtonText: "Supprimer",
        cancelButtonText: "Annuler",
        showCancelButton: true
    }).then(result => {
        if (result.value) {
            deleteAddressSubmit(idAddress);
        }
    });
};

var deletePhoneSubmit = idPhone => {
    let url = `phone/delete/${idPhone}`;

    $.ajax({
        type: "POST",
        url: urlRequest,
        data: { url, type: "DELETE" },
        dataType: "json",
        beforeSend() {
            $("#deleteAddress").attr("disabled", true);
        },
        success(json) {
            if (json.status == true) {
                swal({
                    title: "Suppression",
                    text: json.message,
                    type: "success",
                    confirmButtonText: "Ok",
                    showCancelButton: false
                }).then(result => {
                    if (result.value) {
                        $(`#blockPhone${idPhone}`)
                            .addClass("animated bounceOutUp")
                            .delay(750)
                            .hide(0);
                    }
                });
            } else {
                swal({
                    title: "Suppression",
                    text: "Une erreur est survenue.",
                    type: "warning"
                });
            }
        }
    });
};

var deleteAddressSubmit = idAddress => {
    let url = `address/delete/${idAddress}`;

    $.ajax({
        type: "POST",
        url: urlRequest,
        data: { url, type: "DELETE" },
        dataType: "json",
        beforeSend() {
            $("#deleteAddress").attr("disabled", true);
        },
        success(json) {
            if (json.status == true) {
                swal({
                    title: "Suppression",
                    text: json.message,
                    type: "success",
                    confirmButtonText: "Ok",
                    showCancelButton: false
                }).then(result => {
                    if (result.value) {
                        $(`#blockAdress${idAddress}`)
                            .addClass("animated bounceOutUp")
                            .delay(750)
                            .hide(0);
                    }
                });
            } else {
                swal({
                    title: "Suppression",
                    text: "Une erreur est survenue.",
                    type: "warning"
                });
            }
        }
    });
};

document.getElementById("adresseForm").addEventListener(
    "submit",
    event => {
        event.preventDefault();
        let form = $("#adresseForm");
        let url = form.attr("action");
        let idPerson = $("#idPersonInput").val();
        let persons = { personId: idPerson };
        let type = "POST";
        let data = $(form).serializeToJSON();

        if (url.includes("modify")) {
            type = "PUT";
        }

        $.ajax({
            type: "POST",
            url: urlRequest,
            data: { type, url, data, links: persons },
            dataType: "json",
            beforeSend() {
                $("#adresseForm [type=submit]")
                    .attr("disabled", true)
                    .attr("value", "Envoi en cours..");
            },
            success(json) {
                $("#adresseForm [type=submit]")
                    .attr("disabled", false)
                    .attr("value", "Envoyer");

                if (json.status == true) {
                    location.reload();
                } else {
                    $("#revealAddress").foundation("close");
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

document.getElementById("phoneForm").addEventListener(
    "submit",
    event => {
        event.preventDefault();
        let form = $("#phoneForm");
        let url = form.attr("action");
        let idPerson = $("#idPersonInput").val();
        let persons = { personId: idPerson };
        let type = "POST";
        let data = $(form).serializeToJSON();

        if (url.includes("modify")) {
            type = "PUT";
        }

        $.ajax({
            type: "POST",
            url: urlRequest,
            data: { type, url, data, links: persons },
            dataType: "json",
            beforeSend() {
                $("#phoneForm [type=submit]")
                    .attr("disabled", true)
                    .attr("value", "Envoi en cours..");
            },
            success(json) {
                $("#phoneForm [type=submit]")
                    .attr("disabled", false)
                    .attr("value", "Envoyer");

                if (json.status == true) {
                    location.reload();
                } else {
                    $("#revealPhone").foundation("close");
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

const changeActionAdress = () => {
    console.log('changeAddress')
    $("#adresseForm").attr("action", "address/create");
};

const changeActionPhone = () => {

    console.log('   hone');
    $("#phoneForm").attr("action", "phone/create");
};
