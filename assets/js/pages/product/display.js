var array = [];

$(() => {
  initDatePicker();
});

const initDatePicker = () => {

  var i = 0;
  var dates = $("#datePicker").datepicker({
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
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true,
    beforeShowDay: function (date) {
      var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
      return [array.indexOf(string) == -1]

    },
    onSelect(dateText, el) {

      array.push(dateText);

      var listHours = $("#listHours").html();
      const randomId = makeid();

      $("#listHours")
        .find(":radio")
        .each(function () {

          $(this).attr('name', randomId);

        });


      $("#listDates").append(`
            <div class="item active margin" data-date="${dateText}">

              ${dateText}

              <div class="close" onclick="deleteDate(this)" style="display:block;"></div>
            </div>
            `);
      changeDateAll(this);
      i++;

    }
  });
}

const deleteDate = el => {
  var date = $(el).parent().attr('data-date');

  var index = array.indexOf(date);
  if (index > -1) {
    array.splice(index, 1);
  }

  $("#datepicker").datepicker("refresh");
  $(el).parent().hide();
}


$('.item').click(function () {

  if ($(this).attr('data-full') != 1) {

    if ($(this).hasClass('active')) {

    }
    else {
      if ($(this).parent().hasClass('radio')) {

        var nb = $(this).parent().children('.active').length;
        if (nb != 0) {
          $(this).parent().children('.active').removeClass('active').removeClass('margin').children('.close').fadeOut();
        }

        $(this).parent().addClass('active');
        $(this).children('.close').fadeOut(300);
        $(this).addClass('active').addClass('margin');
        $('.close', this).delay(700).fadeIn(300);

      }
      else {

        $(this).parent().addClass('active');
        $(this).children('.close').fadeOut(300);
        $(this).addClass('active').addClass('margin');
        $('.close', this).delay(700).fadeIn(300);
      }

    }


  }
  else {
    toastr.error('Horaire indisponible.');
  }

});

$('.close').click(function (event) {
  event.stopPropagation();

  var nb = $(this).parent().parent().children('.active').length;

  if (nb == 0) {
    $(this).parent().parent().removeClass('active');
  }


  $(this).parent().removeClass('active').removeClass('margin');
  $(this).fadeOut(300);
});




var changeDate = (data) => {
  $("#sessionDate").val($(data).attr('data-date'));
}

var changeDateAll = (data) => {
  var listDates = '';
  $("#listDates")
    .find(".item.active")
    .each(function () {

      if (listDates != '') {
        listDates = listDates + ',' + $(this).attr('data-date');
      }
      else {
        listDates = $(this).attr('data-date');
      }




    });

  $("#sessionDate").val(listDates);
}




var changeHour = (data) => {
  $("#sessionEnd").val($(data).attr('data-end-hour') + ':00');
  $("#sessionStart").val($(data).attr('data-start-hour') + ':00');
}

var changeLocation = (data) => {
  $("#locationId").val($(data).attr('data-location'));
}

var changePhone = (data) => {
  $("#phone").val($(data).attr('data-id-phone'));
}


var changeAddress = (data) => {
  $("#address").val($(data).attr('data-id-address'));
  $("#postal").val($(data).attr('data-id-postal'));
}


var changeChild = (data) => {
  let priceBase = $("#pricebase").val();
  var i = 0;
  setTimeout(function () {


    var listChild = '';

    $("#listChild")
      .find(".item.active")
      .each(function () {
        i++;

        if (listChild != '') {
          listChild = listChild + ',' + $(this).attr('data-child-id');
        }
        else {

          listChild = $(this).attr('data-child-id');
            listChildAge = $(this).attr('data-child-age');
          $('.multisport').hide();
          $('.allsports').hide();

          console.log(listChildAge);

          if(listChildAge < 7) {
              $('.multisport').show();
          } else if(listChildAge > 6) {
              $('.allsports').show();
          }
        }




      });

    $("#child").val(listChild);


    if (i == 0 || i == 1) {
      $(".amount").html(priceBase);
    }
    else {
      let price = priceBase * i;

      $(".amount").html(price);
    }


  }, 1000);


}


var changeSport = (data) => {
  setTimeout(function () {
    var listSport = '';
    $("#listSports")
      .find(".item.active")
      .each(function () {

        if (listSport != '') {
          listSport = listSport + ',' + $(this).attr('data-sport');
        }
        else {
          listSport = $(this).attr('data-sport');
        }




      });

    $("#sportId").val(listSport);
  }, 1000);
}


const makeid = () => {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

const addToCart = () => {
  let child = $("#child").val();
  let product = $("#product").val();
  let person = $("#personId").val();;
  let status = 'cart';
  let payed = 0;
  let location = $("#locationId").val();
  let sports = $("#sportId").val();
  let sessionDate = $("#sessionDate").val();
  let sessionStart = $("#sessionStart").val();
  let sessionEnd = $("#sessionEnd").val();
  let dropin = $("#dropIn").val();
  let dropoff = $("#dropOff").val();
  let transport = $("#isTransport").val();
  let address = $("#address").val();
  let postal = $("#postal").val();
  let phone = $("#phone").val();

  let preferences = [{ phone, address, postal }];


  let splitSport = sports.split(',');

  if (splitSport.length >= 1) {
    let sportArray = [];

    splitSport.forEach(function (sportData, i = 0) {

      sportArray.push({ sportId: sportData });
      i++;

    });

    sports = sportArray;

  }

  let splitDate = sessionDate.split(',');
  let dateArray = [];

  splitDate.forEach(function (dateData, i = 0) {

    dateArray.push({ date: dateData, start: sessionStart, end: sessionEnd });
    i++;

  });

  sessions = dateArray;


  let splitChild = child.split(',');
  if (address != '' && phone != '') {

    if (sessionStart != '') {


      if (child != '') {

        splitChild.forEach(function (childData, i = 0) {

          let data = { child: childData, product, person, payed, status, location, sports, sessions };
          let url = "registration/create";
          let type = "POST";
          $.ajax({
            type: "POST",
            url: urlRequest,
            data: { type, url, data, preferences },
            dataType: "json",
            beforeSend() {

            },
            success(json) {
              countCart();
              viewCart();
              toastr.success('Ajouté au panier');

              console.log(urlRequest);

              /*
                          swal({
                            title: "Ajouté au panier",
                            text: json.message,
                            type: "success",
                            confirmButtonText: "Voir le panier",
                            cancelButtonText: "Continuer",
                            showCancelButton: true
                          }).then(result => {
                              if (result.value) {
                                  $('#clickToViewCart').trigger('click');
                              } else {
                                window.location.href = urlHost;
                              }
                          });*/
            },
            error(json) {

            }
          });

        });


      }
      else {
        toastr.error('Aucun enfant sélectionné');
      }

    } else {
      toastr.error('Vous devez choisir la plage horaire');
    }

  }
  else {
    toastr.error('Vous devez sélectionné l\'adresse de départ pour le transport.');
  }




}





