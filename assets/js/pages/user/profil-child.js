document
    .getElementById("deleteChild")
    .addEventListener("click", function(event) {
        const idChild = $(this).attr("data-id-child");

        swal({
            title: "Attention",
            text: "La suppression est irréversible.",
            type: "warning",
            confirmButtonText: "Supprimer",
            cancelButtonText: "Annuler",
            showCancelButton: true
        }).then(result => {
            if (result.value) {
                deleteChild(idChild);
            }
        });
    });

var deleteChild = idChild => {
    let url = `child/delete/${idChild}`;

    $.ajax({
        type: "POST",
        url: urlRequest,
        data: { url, type: "DELETE" },
        dataType: "json",
        beforeSend() {
            $("#deleteChild")
                .attr("disabled", true)
                .html("Suppression en cours..");
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

