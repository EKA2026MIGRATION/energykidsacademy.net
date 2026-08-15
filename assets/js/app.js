$(document).ready(function() {
  var isConnected = $("#isConnected").val();

  if (isConnected == "yes") {
    let nbReg = sessionStorage.getItem("nbRegistration");
    if (nbReg != null) {
      $(".nbRegistration").html(nbReg);
    }
    viewCart();
    countCart();
  }
});

var urlHost = $("#urlHost").val();
var tokenAuth = $("#token").val();
var mapsApiKey = $("#mapsApiKey").val();
var urlRequest = $("#urlRequest").val();
var urlPhoto = $("#urlPhoto").val();
var showLoader =
  '<div class="containerLoader"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
var photoProfilDefault = $("#photoProfilDefault").val();
var noPhoto = $("#noPhoto").val();

const viewCart = () => {
  $("#contentCart").html("Chargement en cours ... Merci de patienter.");
  setTimeout(function() {
    $("#contentCart").load(urlHost + "ea/cart/", function() {
    });
  }, 10);
};

function clickToViewCart() {
  $('#clickToViewCart').trigger('click');
}

const deleteCartItem = (regisrationId) => {
  let url = `registration/delete/${regisrationId}`;

  $("#cartItemRegisration" + regisrationId)
    .addClass("animated bounceOutUp")
    .delay(750)
    .hide(0);

  $.ajax({
    type: "POST",
    url: urlRequest,
    data: { url, type: "DELETE" },
    dataType: "json",
    beforeSend() {},
    success(json) {
      if (json.status == true) {
        viewCart();
        countCart();
        toastr.success("Produit supprimé");
      } else {
        toastr.error("Une erreur est survenue");
        viewCart();
      }
    },
  });
};

const countCart = () => {
  setTimeout(function() {
    $.ajax({
      type: "GET",
      url: urlHost + "ea/countCart/",
      dataType: "json",
      beforeSend() {},
      success(json) {
        $(".nbRegistration").html(json.nbRegistration);
        if(parseInt(json.nbRegistration) > 0) {
          $('#cartViewInfoFloat').show();
        }
        sessionStorage.setItem("nbRegistration", json.nbRegistration);
      },
      error(json) {
        console.log(json.responseText);
      },
    });
  }, 100);
};

toastr.options = {
  closeButton: false,
  debug: false,
  newestOnTop: false,
  progressBar: false,
  positionClass: "toast-top-full-width",
  preventDuplicates: false,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};

const formatDate = (date) => {
  var date_string = date.split("-").join("-");
  var date = new Date(date_string);
  return (
    (date.getDate().toString().length > 1
      ? date.getDate()
      : "0" + date.getDate()) +
    "/" +
    (date.getMonth() > 8 ? date.getMonth() + 1 : "0" + (date.getMonth() + 1)) +
    "/" +
    date.getFullYear()
  );
};



/**
 * Author: Oleh Kastornov
 * ver 1.2.0
 * Ukraine
 */
(function(win, doc) {
  var COMPRESSOR_DEFAULTS = {
    toWidth: null,
    toHeight: null,
    mimeType: "image/png",
    speed: "low",
    mode: "strict",
    grayScale: false,
    sepia: false,
    threshold: false,
    vReverse: false,
    hReverse: false,
    quality: 1,
  };
  function ImageCompressor() {
    this.settings = JSON.parse(JSON.stringify(COMPRESSOR_DEFAULTS));
    this.canvas = doc.createElement("canvas");
    this.image = doc.createElement("img");
    this.context = this.canvas.getContext("2d");
    this.listenEvents();
  }
  ImageCompressor.prototype = {
    listenEvents: function() {
      this.image.addEventListener("load", this.imageLoaded.bind(this), false);
      this.image.addEventListener("error", this.imageFault, false);
    },
    imageLoaded: function() {
      this.checkSizes();
      this.compress();
    },
    imageFault: function() {
      throw new Error("Source can not be loaded");
    },
    run: function(src, settings, callback) {
      this.settings = JSON.parse(JSON.stringify(COMPRESSOR_DEFAULTS));
      this.settings.toWidth = settings.toWidth; //|| this.settings.toWidth;
      this.settings.toHeight = settings.toHeight; //|| this.settings.toHeight;
      this.settings.mimeType = settings.mimeType || this.settings.mimeType;
      this.settings.mode =
        settings.mode == "strict" || settings.mode == "stretch"
          ? settings.mode
          : this.settings.mode;
      this.settings.speed = settings.speed || this.settings.speed;
      this.settings.grayScale = settings.hasOwnProperty("grayScale")
        ? settings.grayScale
        : this.settings.grayScale;
      this.settings.sepia = settings.hasOwnProperty("sepia")
        ? settings.sepia
        : this.settings.sepia;
      this.settings.threshold = settings.hasOwnProperty("threshold")
        ? settings.threshold
        : this.settings.threshold;
      this.settings.hReverse = settings.hasOwnProperty("hReverse")
        ? settings.hReverse
        : this.settings.hReverse;
      this.settings.vReverse = settings.hasOwnProperty("vReverse")
        ? settings.vReverse
        : this.settings.vReverse;
      this.imageReceiver = callback;
      this.image.src = src;
    },
    compress: function() {
      if (this.settings.speed !== "high") {
        this.compressWithQuality();
      }

      this.compressAddOn();
    },
    compressAddOn: function() {
      this.naturalAR = this.image.naturalWidth / this.image.naturalHeight;
      this.compressedAR = this.settings.toWidth / this.settings.toHeight;
      this.canvas.width = this.settings.toWidth;
      this.canvas.height = this.settings.toHeight;
      this.context.fillStyle =
        this.settings.mimeType == "image/png"
          ? "rgba(255, 255, 255, 0)"
          : "#FFFFFF";
      this.context.fillRect(
        0,
        0,
        this.settings.toWidth,
        this.settings.toHeight
      );
      if (this.settings.mode == "strict") {
        this.strictResize();
      }
      if (this.settings.mode == "stretch") {
        this.stretchResize();
      }
      this.imageFilters();
      this.imageReverse();
      this.imageReceiver(
        this.canvas.toDataURL(this.settings.mimeType, this.settings.quality)
      );
      this.compressedCanv = null;
    },
    compressWithQuality: function() {
      if (
        this.image.naturalWidth <= this.settings.toWidth &&
        this.image.naturalHeight <= this.settings.toHeight
      ) {
        this.compressAddOn();
        return;
      }
      this.firstCompress();
    },
    firstCompress: function() {
      var canvas = doc.createElement("canvas");
      var context = canvas.getContext("2d");
      var w = this.image.naturalWidth / 2;
      var h = this.image.naturalHeight / 2;
      var toW = this.settings.toWidth,
        toH = this.settings.toHeight;
      if (w < toW || h < toH) {
        return;
      }
      canvas.width = w;
      canvas.height = h;
      context.drawImage(this.image, 0, 0, w, h);
      w = w / 2;
      h = h / 2;
      while (w > toW && h > toH) {
        context.drawImage(canvas, 0, 0, w * 2, h * 2, 0, 0, w, h);
        w = w / 2;
        h = h / 2;
      }
      this.compressedCanv = canvas;
      this.compressedW = w * 2;
      this.compressedH = h * 2;
    },
    strictResize: function() {
      if (this.naturalAR >= this.compressedAR) {
        this.fitWidth();
      } else {
        this.fitHeight();
      }
    },
    stretchResize: function() {
      if (this.compressedCanv) {
        this.context.drawImage(
          this.compressedCanv,
          0,
          0,
          this.compressedW,
          this.compressedH,
          0,
          0,
          this.settings.toWidth,
          this.settings.toHeight
        );
      } else {
        this.context.drawImage(
          this.image,
          0,
          0,
          this.settings.toWidth,
          this.settings.toHeight
        );
      }
    },
    fitWidth: function() {
      var compressedHeight = this.settings.toWidth / this.naturalAR,
        offsetX = 0,
        offsetY = (this.settings.toHeight - compressedHeight) / 2;
      if (this.compressedCanv) {
        this.context.drawImage(
          this.compressedCanv,
          0,
          0,
          this.compressedW,
          this.compressedH,
          offsetX,
          offsetY,
          this.settings.toWidth,
          compressedHeight
        );
      } else {
        this.context.drawImage(
          this.image,
          offsetX,
          offsetY,
          this.settings.toWidth,
          compressedHeight
        );
      }
    },
    fitHeight: function() {
      var compressedWidth = this.settings.toHeight * this.naturalAR,
        offsetY = 0,
        offsetX = (this.settings.toWidth - compressedWidth) / 2;
      if (this.compressedCanv) {
        this.context.drawImage(
          this.compressedCanv,
          0,
          0,
          this.compressedW,
          this.compressedH,
          offsetX,
          offsetY,
          compressedWidth,
          this.settings.toHeight
        );
      } else {
        this.context.drawImage(
          this.image,
          offsetX,
          offsetY,
          compressedWidth,
          this.settings.toHeight
        );
      }
    },
    checkSizes: function() {
      var aspectRatio = this.image.naturalWidth / this.image.naturalHeight;
      if (!this.settings.toWidth && !this.settings.toHeight) {
        this.settings.toWidth = this.image.naturalWidth;
        this.settings.toHeight = this.image.naturalHeight;
        return;
      }
      if (!this.settings.toWidth) {
        this.settings.toWidth = this.settings.toHeight * aspectRatio;
        return;
      }
      if (!this.settings.toHeight) {
        this.settings.toHeight = this.settings.toWidth / aspectRatio;
      }
    },
    grayScale: function() {
      var imgData = this.context.getImageData(
        0,
        0,
        this.canvas.width,
        this.canvas.height
      );
      var pixels = imgData.data;
      var grayValue;
      for (var i = 0, n = imgData.data.length; i < n; i += 4) {
        if (imgData.data[i + 3] == 0) {
          continue;
        }
        grayValue =
          (imgData.data[i] + imgData.data[i + 1] + imgData.data[i + 2]) / 3;
        imgData.data[i] = grayValue;
        imgData.data[i + 1] = grayValue;
        imgData.data[i + 2] = grayValue;
      }
      this.context.putImageData(imgData, 0, 0);
    },
    sepia: function() {
      var imgData = this.context.getImageData(
        0,
        0,
        this.canvas.width,
        this.canvas.height
      );
      var pixels = imgData.data;
      var grayValue;
      for (var i = 0, n = imgData.data.length; i < n; i += 4) {
        if (imgData.data[i + 3] == 0) {
          continue;
        }
        imgData.data[i] =
          imgData.data[i] * 0.393 +
          imgData.data[i + 1] * 0.769 +
          imgData.data[i + 2] * 0.189;
        imgData.data[i + 1] =
          imgData.data[i] * 0.349 +
          imgData.data[i + 1] * 0.686 +
          imgData.data[i + 2] * 0.168;
        imgData.data[i + 2] =
          imgData.data[i] * 0.272 +
          imgData.data[i + 1] * 0.534 +
          imgData.data[i + 2] * 0.131;
      }
      this.context.putImageData(imgData, 0, 0);
    },
    threshold: function() {
      var imgData = this.context.getImageData(
        0,
        0,
        this.canvas.width,
        this.canvas.height
      );
      var pixels = imgData.data;
      var averageIntensity;
      var thresholdVal;
      var grayValue;
      for (var i = 0, n = imgData.data.length; i < n; i += 4) {
        if (imgData.data[i + 3] == 0) {
          continue;
        }
        averageIntensity =
          (imgData.data[i] + imgData.data[i + 1] + imgData.data[i + 2]) / 3;
        thresholdVal = averageIntensity > this.settings.threshold ? 255 : 0;
        imgData.data[i] = thresholdVal;
        imgData.data[i + 1] = thresholdVal;
        imgData.data[i + 2] = thresholdVal;
      }
      this.context.putImageData(imgData, 0, 0);
    },
    imageFilters: function() {
      if (this.settings.grayScale) {
        this.grayScale();
        return;
      }
      if (this.settings.sepia) {
        this.sepia();
        return;
      }
      if (this.settings.threshold) {
        this.threshold();
        return;
      }
    },

    imageReverse: function() {
      if (this.settings.vReverse) {
        this.vReverse();
      }

      if (this.settings.hReverse) {
        this.hReverse();
      }
    },

    vReverse: function() {
      var imgData = this.context.getImageData(
        0,
        0,
        this.canvas.width,
        this.canvas.height
      );
      var pixels = imgData.data;
      var reversedData = this.context.createImageData(
        this.canvas.width,
        this.canvas.height
      );
      // var chunk = [];
      var vIndex = this.canvas.height;
      var fullIndex = 0;
      var reversedIndex = 0;
      var hCount = this.canvas.width * 4;
      for (var i = vIndex - 1; i >= 0; i--) {
        fullIndex = i * hCount;
        for (var j = 0; j < hCount; j += 4) {
          reversedData.data[reversedIndex] = pixels[fullIndex + j];
          reversedData.data[reversedIndex + 1] = pixels[fullIndex + j + 1];
          reversedData.data[reversedIndex + 2] = pixels[fullIndex + j + 2];
          reversedData.data[reversedIndex + 3] = pixels[fullIndex + j + 3];
          reversedIndex += 4;
        }
      }

      this.context.putImageData(
        reversedData,
        0,
        0,
        0,
        0,
        this.canvas.width,
        this.canvas.height
      );
    },

    hReverse: function() {
      var imgData = this.context.getImageData(
        0,
        0,
        this.canvas.width,
        this.canvas.height
      );
      var pixels = imgData.data;
      var reversedData = this.context.createImageData(
        this.canvas.width,
        this.canvas.height
      );
      var vIndex = this.canvas.height;
      var fullIndex = 0;
      var loopWidth = 0;
      var reversedIndex = 0;
      var hCount = this.canvas.width * 4;
      for (var i = hCount - 4; i > 0; i -= 4) {
        for (var j = 0; j < vIndex; j++) {
          loopWidth = j * hCount;
          fullIndex = loopWidth + i;
          reversedIndex = loopWidth + hCount - i;
          reversedData.data[reversedIndex] = pixels[fullIndex];
          reversedData.data[reversedIndex + 1] = pixels[fullIndex + 1];
          reversedData.data[reversedIndex + 2] = pixels[fullIndex + 2];
          reversedData.data[reversedIndex + 3] = pixels[fullIndex + 3];
        }
      }

      this.context.putImageData(
        reversedData,
        0,
        0,
        0,
        0,
        this.canvas.width,
        this.canvas.height
      );
    },
  };
  win.ImageCompressor = ImageCompressor;
})(window, document);
