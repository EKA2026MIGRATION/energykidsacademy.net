var datesClosed = [];
var datesAlmostClosed = [];

const convertDate = (date) => {
  var yyyy = date.getFullYear().toString();
  var mm = (date.getMonth() + 1).toString();
  var dd = date.getDate().toString();

  var mmChars = mm.split("");
  var ddChars = dd.split("");

  return (
    yyyy +
    "-" +
    (mmChars[1] ? mm : "0" + mmChars[0]) +
    "-" +
    (ddChars[1] ? dd : "0" + ddChars[0])
  );
};

const arraySearch = (arr, val) => {
  for (var i = 0; i < arr.length; i++)
    if (arr[i] === val) return i;
  return false;
};

$(() => {
  var countDate = [];
  var todaysDate = new Date();
  var dateWithDay = convertDate(todaysDate);
  var date = dateWithDay.substring(0, dateWithDay.length - 3);

  let url = "product-cancelled-date/list?page=1&size=100";
  let type = "GET";
  $.ajax({
    type: "POST",
    url: urlRequest,
    data: {
      type,
      url
    },
    dataType: "json",
    beforeSend() {},
    success(data) {
      if (!Array.isArray(data)) {
        console.error('API product-cancelled-date error:', data);
        return;
      }
      data.forEach(function (json) {
        if (json.category != null) {
          if (json.category.categoryId == $("#categoryId").val()) {
            countDate.push(json.date);
            var count = [];

            countDate.forEach(function (i) {
              count[i] = (count[i] || 0) + 1;

              if (count[i] == 3) {
                if (datesClosed.includes(i)) {} else {
                  datesClosed.push(i);
                }
              } else {
                if (count[i] > 0) {

                  if (datesAlmostClosed.includes(i)) {} else {
                    datesAlmostClosed.push(i);
                  }

                }
              }
            });
          }
        }
      });

      initDatePicker();
    },
    error(json) {},
  });
});

const initDatePicker = () => {
  var i = 0;
  var dates = $("#datepicker").datepicker({
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
      "Décembre",
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
      "Déc.",
    ],
    dayNames: [
      "Dimanche",
      "Lundi",
      "Mardi",
      "Mercredi",
      "Jeudi",
      "Vendredi",
      "Samedi",
    ],
    dayNamesShort: ["Dim.", "Lun.", "Mar.", "Mer.", "Jeu.", "Ven.", "Sam."],
    dayNamesMin: ["D", "L", "M", "M", "J", "V", "S"],
    weekHeader: "Sem.",
    dateFormat: "yy-mm-dd",
    beforeShowDay: function (mydate) {
      let arr = [];
      let currentDate = convertDate(mydate);

      if (datesClosed.includes(currentDate)) {
        $('#showLegend').show();
        $('#showFullLegend').show();
        return ["", "dateClosedDatePicker"];
      } else {
        if (datesAlmostClosed.includes(currentDate)) {
          $('#showLegend').show();
          $('#showAlmostLegend').show();
          return [true, "dateAlmostClosedDatePicker", ""];
        } else {
          return [true, "dateAvailableDatePicker", ""];
        }
      }
    },
    changeYear: true,
    changeMonth: true,
    minDate: 0,
    firstDay: 1,

    onSelect(dateText, el) {
      var isUse = 0;
      $("#listDate")
        .find(".item")
        .each(function () {
          let date = $(this).attr("data-sessionDate");

          if (date == dateText) {
            isUse = 1;
          }
        });

      if (isUse == 0) {
        let address = $("#address").val();

        if (address != "") {
          $("#contentProduct").html("");
          $("#sessionDate").val(dateText);
          localStorage.setItem("autocompleteALaCarteEdit", false);
          $("#modal-alacarte-open").trigger("click");
          $(".choiceProduct")
            .find("input:first-of-type")
            .trigger("click");
        } else {
          toastr.error(
            "Vous devez sélectionné l'adresse de départ pour le transport."
          );
        }
      } else {
        toastr.error("Vous avez déjà sélectionné cette date.");
      }

      i++;
    },
  });
};

$(".item").on("click", function () {
  if ($(this).hasClass("active")) {} else {
    if (
      $(this)
      .parent()
      .hasClass("radio")
    ) {
      var nb = $(this)
        .parent()
        .children(".active").length;
      if (nb != 0) {
        $(this)
          .parent()
          .children(".active")
          .removeClass("active")
          .removeClass("margin")
          .children(".close")
          .fadeOut();
      }

      $(this)
        .parent()
        .addClass("active");
      $(this)
        .children(".close")
        .fadeOut(300);
      $(this)
        .addClass("active")
        .addClass("margin");
      $(".close", this)
        .delay(700)
        .fadeIn(300);
    } else {
      $(this)
        .parent()
        .addClass("active");
      $(this)
        .children(".close")
        .fadeOut(300);
      $(this)
        .addClass("active")
        .addClass("margin");
      $(".close", this)
        .delay(700)
        .fadeIn(300);
    }
  }
});

const changeStateProduct = (e) => {
  var nb = $(e)
    .parent()
    .parent()
    .children(".active").length;

  if (nb == 0) {
    $(e)
      .parent()
      .parent()
      .removeClass("active");
  }

  $(e)
    .parent()
    .removeClass("active")
    .removeClass("margin");
  $(e).fadeOut(300);
};

const openProduct = (e, event) => {
  if (
    $(e)
    .children(".close")
    .is(":visible")
  ) {} else {
    $(e)
      .addClass("active")
      .addClass("margin");
    $(e)
      .children(".close")
      .fadeIn(300);
  }
};

$(".close").on("click", function (event) {
  event.stopPropagation();
  var nb = $(this)
    .parent()
    .parent()
    .children(".active").length;

  if (nb == 0) {
    $(this)
      .parent()
      .parent()
      .removeClass("active");
  }

  $(this)
    .parent()
    .removeClass("active")
    .removeClass("margin");
  $(this).fadeOut(300);
});

var loadProduct = (el) => {
  let nameProduct = $(el).attr("data-product-name");
  let idProduct = $(el).attr("data-id-product");
  $('.choiceProduct').find('.messageDisabled').remove();
  $('.choiceProduct').find('input:disabled').attr('disabled', false);
  $("#contentProduct").load(
    urlHost + "ea/loadProductALacarte/id/" + idProduct + "/",
    function () {}
  );
  $("#contentProduct").trigger("create");

  $("#productName").val(nameProduct);
  let date = $("#sessionDate").val();

  let url =
    "product-cancelled-date/list-category/" +
    $("#categoryId").val() +
    "/" +
    date;
  let type = "GET";
  $.ajax({
    type: "POST",
    url: urlRequest,
    data: {
      type,
      url
    },
    dataType: "json",
    beforeSend() {},
    success(data) {
      data.forEach(function (json) {
        $("#product" + json.product.productId).attr("disabled", true);
        $("[for=product" + json.product.productId + "]").html(
          `<span class="full"> ${json.product.nameFr} <span class="messageDisabled">${json.messageFr}</span></span>`
        );
        var productValue = $("#product").val();

        if (productValue == json.product.productId) {
          $("#product").val("");
          $("#product" + json.product.productId).prop("checked", false);
        }
      });
    },
    error(json) {
      console.log("no result");
    },
  });
};

const openModalModif = (e) => {
  var parent = $(e).parent();
  let child = $(parent).attr("data-child");
  let product = $(parent).attr("data-product");
  let person = $(parent).attr("data-person");
  let location = $(parent).attr("data-location");
  let sports = $(parent).attr("data-sports");
  let sessionDate = $(parent).attr("data-sessionDate");
  let sessionStart = $(parent).attr("data-sessionStart");
  let sessionEnd = $(parent).attr("data-sessionEnd");
  let dropin = $(parent).attr("data-dropin");
  let dropoff = $(parent).attr("data-dropoff");
  let transport = $(parent).attr("data-transport");

  localStorage.setItem("autocompleteALaCarteEdit", true);
  localStorage.setItem("aLaCarteChild", child);
  localStorage.setItem("aLaCarteSport", sports);

  $("#modal-alacarte-open").trigger("click");
  $(".choiceProduct")
    .find("#product" + product)
    .trigger("click");
};

const addToList = () => {
  let price = $("#price").val();
  let priceTotalInput = $("#priceTotal").val();
  let child = $("#child").val();
  let childName = $("#childName").val();
  let productName = $("#productName").val();
  let product = $("#product").val();
  let person = $("#personId").val();
  let location = $("#locationId").val();
  let sports = $("#sportId").val();
  let sportsName = $("#sportsName").val();
  let sessionDate = $("#sessionDate").val();
  let sessionStart = $("#sessionStart").val();
  let sessionEnd = $("#sessionEnd").val();
  let dropin = $("#dropIn").val();
  let dropoff = $("#dropOff").val();
  let transport = $("#isTransport").val();

  if (product == '') {
    toastr.error("Veuillez choisir un produit.");

    return false;
  }

  if (child != "" && sports != "") {
    var isUse = 0;
    $("#listDate")
      .find(".item")
      .each(function () {
        let date = $(this).attr("data-sessionDate");

        if (date == sessionDate) {
          isUse = 1;
        }
      });

    localStorage.setItem("aLaCarteChild", child);
    localStorage.setItem("aLaCarteSport", sports);

    if (isUse == 0) {
      $("#listDate").append(`
        <div data-date-session="${sessionDate}">
        <div
          data-child="${child}"
          data-product="${product}"
          data-person="${person}"
          data-location="${location}"
          data-sports="${sports}"
          data-sessionDate="${sessionDate}"
          data-sessionStart="${sessionStart}"
          data-sessionEnd="${sessionEnd}"
          data-transport="${transport}"
          data-dropin="${dropin}"
          data-dropoff="${dropoff}"
          data-price="${priceTotalInput}"
          class="item active margin" 
          style="height: auto; width: 150px; font-size: 13px"
          onclick="openProduct(this)"
        >
          <div class="close" onclick="changeStateProduct(this)"  style="display: block; top: 16px; right: 0px"></div>
          ${formatDate(
            sessionDate
          )}<br/> ${childName} <br/> ${productName} <br/> ${sportsName} <br/> ${priceTotalInput} euros <br/>
          <a href="javascript:void(0)" onclick="openModalModif(this)" style="font-size: 13px; right: 0px; top: 16px">Modifier</a>
        </div>
        </div>
      `);
      $("#modal-alacarte-open").trigger("click");
      calculPrice();
      addToCart();
        $("#listDate").empty();
    } else {
      $('[data-date-session="' + sessionDate + '"]').html(`
      <div
      data-child="${child}"
      data-product="${product}"
      data-person="${person}"
      data-location="${location}"
      data-sports="${sports}"
      data-sessionDate="${sessionDate}"
      data-sessionStart="${sessionStart}"
      data-sessionEnd="${sessionEnd}"
      data-transport="${transport}"
      data-dropin="${dropin}"
      data-dropoff="${dropoff}"
      data-price="${priceTotalInput}"
      class="item active margin" 
      style="height: auto; width: 150px; font-size: 13px"
      onclick="openProduct(this)"
    >
      <div class="close" onclick="changeStateProduct(this)"  style="display: block; top: 16px; right: 0px"></div>
      ${formatDate(
        sessionDate
      )}<br/> ${childName} <br/> ${productName} <br/> ${sportsName} <br/> ${priceTotalInput} euros <br/>
      <a href="javascript:void(0)" onclick="openModalModif(this)" style="font-size: 13px; right: 0px; top: 16px">Modifier</a>
    </div>
    </div> `);
      $("#modal-alacarte-open").trigger("click");
      calculPrice();
    }
  } else {
    toastr.error("Vous devez sélectionner au moins un sport et un enfant.");
  }
};

const calculPrice = () => {
  var pTotal = 0;
  $("#listDate")
    .find(".item")
    .each(function () {
      let price = $(this).attr("data-price");
      pTotal = parseInt(pTotal) + parseInt(price);
    });

  $(".amountTotal").html(pTotal);
};

const addToCart = () => {
  var i = 0;
  var length = $("#listDate").find(".item.active").length;

  if (length == 0) {
    toastr.error("Aucune date sélectionnée.");
  }

  var error = 0;

  $("#listDate")
    .find(".item.active")
    .each(function () {
      let child = $(this).attr("data-child");
      let product = $(this).attr("data-product");
      let person = $(this).attr("data-person");
      let status = "cart";
      let payed = 0;
      let location = $(this).attr("data-location");
      let sports = $(this).attr("data-sports");
      let sessionDate = $(this).attr("data-sessionDate");
      let sessionStart = $(this).attr("data-sessionStart");
      let sessionEnd = $(this).attr("data-sessionEnd");
      let dropin = $(this).attr("data-dropin");
      let dropoff = $(this).attr("data-dropoff");
      let transport = $(this).attr("data-transport");
      let address = $("#address").val();
      let postal = $("#postal").val();
      let phone = $("#phone").val();

      let preferences = [{
        phone,
        address,
        postal
      }];

      let splitSport = sports.split(",");

      if (splitSport.length >= 1) {
        let sportArray = [];

        splitSport.forEach(function (sportData, i = 0) {
          sportArray.push({
            sportId: sportData
          });
          i++;
        });

        sports = sportArray;
      }

      let splitDate = sessionDate.split(",");
      let dateArray = [];

      dateArray.push({
        date: $('#sessionDate').val(),
        start: sessionStart,
        end: sessionEnd,
      });
      i++;

      sessions = dateArray;

      let splitChild = child.split(",");

      if ($("#sportId").val() != "") {
        if (address != "" && phone != "") {
          if (child != "") {
            splitChild.forEach(function (childData, i = 0) {
              let data = {
                child: childData,
                product,
                person,
                payed,
                status,
                location,
                sports,
                sessions,
              };

              let url = "registration/create";
              let type = "POST";
              $.ajax({
                type: "POST",
                url: urlRequest,
                data: {
                  type,
                  url,
                  data,
                  preferences
                },
                dataType: "json",
                beforeSend() {},
                success(json) {

                  if (json.status == false) {

                    for (let j = 0; j < json.messages.length; j++) {
                      console.log(json.messages);
                      toastr.error("Inscription impossible, il y a déjà une inscription pour " + json.messages[j].name + " pour le " + json.messages[j].date_fr);
                    }
                    error = 1;
                  } else {
                    countCart();
                    viewCart();
                    toastr.success("Ajouté au panier");
                    $(".add-cart > input").fadeOut();
                  }
                },
                error(json) {},
              });
            });
          } else {
            toastr.error("Aucun enfant sélectionné");
            error = 1;
          }
        } else {
          toastr.error(
            "Vous devez sélectionné l'adresse de départ pour le transport."
          );
          error = 1;
        }
      } else {
        toastr.error("Vous devez sélectionné au moins un sport.");
        error = 1;
      }

      i++;
      /*
            if (length == i && error == 0) {
              countCart();
              viewCart();
              toastr.success("Ajouté au panier");
              $(".add-cart > input").fadeOut();
            }*/
    });
};

var changePhone = (data) => {
  $("#phone").val($(data).attr("data-id-phone"));
};

var changeAddress = (data) => {
  $("#address").val($(data).attr("data-id-address"));
  $("#postal").val($(data).attr("data-id-postal"));
};