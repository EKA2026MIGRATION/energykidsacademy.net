$('.addToCartProduct').click(function(e) {
  e.preventDefault();

  let child = $(this).data('childid');
  let product = $(this).data('productid');
  let person = $(this).data('personid');


  let data = { child: child, product : product, person: person, payed: 0, status: "cart", sessions : [], sports : []};
  let preferences = [];

  let url = "registration/create";
  let type = "POST";

  $.ajax({
    type: "POST",
    url: urlRequest,
    data: {type, url, data, preferences},
    dataType: "json",
    beforeSend() {
  
    },
    success(json) {
      countCart();
      viewCart();
      toastr.success('Ajouté au panier');

    },
    error(json)
    {
      console.log(json);
    }
  });
  

  return false;

});