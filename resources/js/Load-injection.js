(function ($) {
  $(document).ready(function () {
    var table = $(".shippingList").DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
      },
      order: [[0, "asc"]],
      columnDefs: [
        {
          targets: [0],
          orderable: false,
        },
      ],
      lengthMenu: [
        [30, 45, 60, -1],
        [30, 45, 60, "Todos"],
      ],
      pageLength: 10,
    });

    /*** section configuration */

    // Load configuration
    $.ajax({
      async: false,
      url: "admin-ajax.php",
      type: "POST",
      data: {
        action: "load_configuration",
        nonce: " load_configuration",
      },
      success: function (response) {
        var parse = JSON.parse(response);


        if (parse != null) {
          /**Input accountNumber */
          $("#accountNumber").val(parse["configuration"]["accountNumber"]);

          /***************************************************** */

          /**Input meterNumber */
          $("#meterNumber").val(parse["configuration"]["meterNumber"]);

          /***************************************************** */

          /** input wskeyUserCredential */
          $("#wskeyUserCredential").val(
            parse["configuration"]["wskeyUserCredential"]
          );

          /***************************************************** */

          /**input wskeyPasswordCredential */
          $("#wskeyPasswordCredential").val(
            parse["configuration"]["wskeyPasswordCredential"]
          );

          /***************************************************** */

          $("#serviceType").val(parse["configuration"]["serviceType"]);
          $("#packagingType").val(parse["configuration"]["packagingType"]);
          $("#paymentType").val(parse["configuration"]["paymentType"]);
          $("#labelType").val(parse["configuration"]["labelType"]);
          $("#measurementUnits").val(
            parse["configuration"]["measurementUnits"]
          );

          /***************************************************** */

          //activate checkbox
          if (parse["configuration"]["flagInsurance"] == "1") {
            $("#flagInsurance").prop("checked", true);

            document.getElementById("width").readOnly = false;
            document.getElementById("length").readOnly = false;
            document.getElementById("height").readOnly = false;

            // parse dimemsion cm
            $("#width").val(parse["configuration"]["width"]);
            $("#height").val(parse["configuration"]["height"]);
            $("#length").val(parse["configuration"]["length"]);
          } else {

            $("#flagInsurance").prop("checked", false);
             // parse dimemsion cm
             $("#width").val(parse["configuration"]["width"]);
             $("#height").val(parse["configuration"]["height"]);
             $("#length").val(parse["configuration"]["length"]);
          }

          /***************************************************** */

          $("#environment").val(parse["configuration"]["environment"]);


          /****************************************************** */

          $("#endPointRate").val(parse["configuration"]["endPointRate"]);
          $("#endPointShip").val(parse["configuration"]["endPointShip"]);
          $("#endPointConfirmation").val(parse["configuration"]["endPointConfirmation"]);
          $("#endPointPrintLabel").val(parse["configuration"]["endPointPrintLabel"]);
          $("#endPointCancel").val(parse["configuration"]["endPointCancel"]);
          $("#endPointPrintManifestPdf").val(parse["configuration"]["endPointPrintManifestPdf"]);

          /****************************************************** */


          /**Input Data Origin */
          $("#personNameShipper").val(parse["shipper"]["personNameShipper"]);
          $("#phoneShipper").val(parse["shipper"]["phoneShipper"]);
          $("#companyNameShipper").val(parse["shipper"]["companyNameShipper"]);
          $("#emailShipper").val(parse["shipper"]["emailShipper"]);
          $("#vatNumberShipper").val(parse["shipper"]["vatNumberShipper"]);
          $("#cityShipper").val(parse["shipper"]["cityShipper"]);

          parse["shipper"]["stateOrProvinceCodeShipper"] == ""
            ? $("#stateOrProvinceCodeShipper").val("CL")
            : $("#stateOrProvinceCodeShipper").val(
                parse["shipper"]["stateOrProvinceCodeShipper"]
              );
          // $('#stateOrProvinceCodeShipper').val(parse['shipper']['stateOrProvinceCodeShipper);
          $("#postalCodeShipper").val(parse["shipper"]["postalCodeShipper"]);

          parse["shipper"]["countryCodeShipper"] == null
            ? $("#countryCodeShipper").val("CL")
            : $("#countryCodeShipper").val(
                parse["shipper"]["countryCodeShipper"]
              );

          //$('#countryCodeShipper').val(parse.countryCodeShipper);
          $("#addressLine1Shipper").val(
            parse["shipper"]["addressLine1Shipper"]
          );
          $("#addressLine2Shipper").val(
            parse["shipper"]["addressLine2Shipper"]
          );
          $("#taxIdShipper").val(parse["shipper"]["taxIdShipper"]);
          $("#ieShipper").val(parse["shipper"]["ieShipper"]);
        }
      },

      error: function (error) {
        console.log(error);
      },
    });

    /******************************************************* */

    //Envio de formulario de configuración datos cliente
    jQuery("#configuration").on("submit", function (e) {
      e.preventDefault();

      let inputs = $("#configuration").serializeArray();

      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          inputs: inputs,
          action: "save_configuration",
        },

        beforeSend: function () {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "info",
            title: "Enviando solicitud",
            text: "Espere un momento...",
          });
        },
        success: function (data) {
          let timerInterval;
          Swal.fire({
            title: "Autorizado",
            icon: "success",
            html: data,
            timer: 1000,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              const b = Swal.getHtmlContainer().querySelector("b");
              timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft();
              }, 100);
            },
            willClose: () => {
              clearInterval(timerInterval);
            },
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              location.reload();
            }
          });
        },
        error: function (data) {
          console.log(data);
        },
      });
    });

    /******************************************************* */

    /**Envio de Formulario OriginShipper */
    jQuery("#originShipper").on("submit", function (e) {
      e.preventDefault();

      let inputs = $("#originShipper").serializeArray();

      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          inputs: inputs,
          action: "save_originShipper",
        },

        beforeSend: function () {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "info",
            title: "Enviando solicitud",
            text: "Espere un momento...",
          });
        },
        success: function (data) {

          let timerInterval;
          Swal.fire({
            title: "Autorizado",
            icon: "success",
            html: data,
            timer: 1000,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              const b = Swal.getHtmlContainer().querySelector("b");
              timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft();
              }, 100);
            },
            willClose: () => {
              clearInterval(timerInterval);
            },
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              location.reload();
            }
          });
        },
        completed: function () {},
        error: function (data) {
          Swal.fire({
            title: "Error",
            text: "Error al guardar los datos de origen",
            icon: "error",
            confirmButtonText: "Cerrar",
          });
        },
      });
    });

    /********************************************************************************************* */

    // Lista de ordenes de compra
    $(".itemsOrder").click(function () {
      //$('#modal-itemsOrder').modal('show');

      $.ajax({
        url: "admin-ajax.php", // Url to which the request is send
        type: "POST",
        data: {
          action: "fedex_shipping_intra_Chile_get_order_detail",
          orderId: $(this).attr("data-order"),
        },
        beforeSend: function () {},
        success: function (data) {
          $("#modal-itemsOrder").modal("show");

          let parse = JSON.parse(data);

          let html = "";
          let i = 1;

          parse.items.forEach(function (item) {

            html += "<tr>";
            html += "<td>" + i + "</td>";
            html += "<td>" + item.name + "</td>";
            html += "<td>" + item.quantity + "</td>";
            html += "<td>" + item.total + "</td>";

            i++;

            html += "</tr>";
          });

          $("#itemsOrder").html(html);
        },
        error: function (data) {
          console.log(data);
        },
      });
    });

    /********************************************************************************************* */

    /** section create-shipping */

    $(".create_shipping").click(function () {
      $.ajax({
        url: "admin-ajax.php",
        type: "POST",
        data: {
          orderId: $(this).data("order"),
          action: "fedex_shipping_intra_Chile_get_order_detail",
          security: $(
            "#fedex_shipping_intra_Chile_create_shipping_nonce"
          ).val(),
          order_id: $("#fedex_shipping_intra_Chile_order_id").val(),
        },
        beforeSend: function () {},
        success: function (response) {
          var parse = JSON.parse(response);


          $("#orderNumber").val(parse.number);
          $("#orderNumber").attr("readonly", true);
          // $("#orderDate").val(today);
          $("#personNameRecipient").val(
            parse["billing"]["first_name"] + " " + parse["billing"]["last_name"]
          );
          $("#phoneNumberRecipient").val(
            parse["billing"]["phone"].replace("+56", "")
          );
          $("#companyNameRecipient").val(parse["billing"]["company"]);
          $("#emailRecipient").val(parse["billing"]["email"]);

          var customer_note = (parse["customer_note"]).slice(0,100);

          if (parse["customer_note"] != "") {
            $("#notesRecipient").val(customer_note);
          } else {
            $("#notesRecipient").val("Ver nota fiscal".slice(0, 100));
          }

          $("#vatNumberRecipient").val(
            parse["billing"]["vat_number"] == ""
              ? parse["billing"]["vat_number"]
              : "1-9"
          );
          $("#cityRecipient").val(parse["billing"]["city"]);
          $("#stateOrProvinceCodeRecipient").val(parse["billing"]["state"]);
          $("#postalCodeRecipient").val(parse["billing"]["postcode"]);
          $("#countryCodeRecipient").val(parse["billing"]["country"]);
          $("#streetLine1Recipient").val(
            parse["billing"]["address_1"].slice(0, 40)
          );
          $("#streetLine2Recipient").val(
            parse["billing"]["address_2"].slice(0, 40)
          );

          $("#numberOfPieces").val(parse["orderDetail"]["quantity"]);
          $("#weightUnits").val(parse["orderDetail"]["weightUnits"]);

          if (parse.flagInsurance != 1) {
            $("#length").val(parse["orderDetail"]["length"]);
            $("#width").val(parse["orderDetail"]["width"]);
            $("#height").val(parse["orderDetail"]["height"]);

            var volume = (
              (parse["orderDetail"]["length"]/100) *
              (parse["orderDetail"]["width"]/100) *
              (parse["orderDetail"]["height"]/100)
            ).toFixed(3);
          } else {
            $("#length").val(parse.length);
            $("#width").val(parse.width);
            $("#height").val(parse.height);

            var volume = ((parse.length/100) * (parse.width/100) * (parse.height/100)).toFixed(3);
          }

          if (
            parse["orderDetail"]["weightUnits"] == "g" ||
            parse["orderDetail"]["weightUnits"] == "gr" ||
            parse["orderDetail"]["weightUnits"] == "kg" ||
            parse["orderDetail"]["weightUnits"] == "kgr"
          ) {
            var volumeRect = volume;
            $("#weight").val(parse["orderDetail"]["weight"]);

            var weightVol = ((parse["orderDetail"]["weight"])/250).toFixed(3);

            if (weightVol < 0.001) {
              $("#volume").val(0.001);
            } else {
              $("#volume").val(weightVol);
            }

            $("#dimensionUnits").val("m3");
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    // Crear envío definitivo hacia FedEx
    $(document).on("submit", "#orderSend", function (e) {
      e.preventDefault();

      let inputs = $(this).serializeArray();

      Swal.fire({
        title: "¿Deseas crear el envío?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, crear",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: "admin-ajax.php", // Url to which the request is send
            type: "POST",
            data: {
              inputs: inputs,
              action: "fedex_shipping_intra_Chile_create_OrderShipper",
            },
            beforeSend: function () {
              const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
              });

              Toast.fire({
                icon: "info",
                title: "Solicitando el servicio",
                text: "Espere un momento...",
              });
            },

            success: function (data) {
              var parseData = JSON.parse(data);

              if (parseData.status == "Autorizado") {
                Swal.fire({
                  title: parseData.message,
                  icon: "success",
                  html:
                    "ORDEN DE TRANSPORTE #<br>" +
                    parseData.masterTrackingNumber +
                    "</br>",
                  showDenyButton: false,
                  showCancelButton: false,
                  confirmButtonText: "OK",
                  denyButtonText: "Cancelar",
                }).then((result) => {
                  /* Read more about isConfirmed, isDenied below */
                  if (result.isConfirmed) {
                    location.reload();
                  } else if (result.isDenied) {
                    //Swal.fire('Changes are not saved', '', 'info')
                  }
                });
              } else {
                Swal.fire({
                  title: "Error",
                  icon: "error",
                  html:
                    parseData.message + ": <b>" + parseData.comments + "</b>",
                });
              }
            },
            completed: function () {},

            error: function (data) {
              console.log(data);
              Swal.fire({
                title: "Error",
                text: "Error al guardar los datos",
                icon: "error",
                confirmButtonText: "Cerrar",
              });
            },
          });
        }
      });
    });

    /** section create-shipping */

    $(".printOneLabel").click(function () {
      $.ajax({
        url: "admin-ajax.php",
        type: "POST",
        data: {
          orderId: $(this).data("order"),
          action: "fedex_shipping_intra_Chile_print_label",
        },
        beforeSend: function () {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "info",
            title: "Imprimiendo etiqueta",
            text: "Espere un momento...",
          });
        },
        success: function (response) {
          var parseData = JSON.parse(response);


          var pdfWindow = window.open("");
          pdfWindow.document.write("<title>FedEx Shipping Label</title>");

          parseData.forEach((element) => {
            pdfWindow.document.write(
              "<embed width='100%' height='100%' src='data:application/pdf;base64, " +
                encodeURI(element.labelBase64) +
                "#toolbar=1&navpanes=0&scrollbar=0'>"
            );
          });
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    // Confirmacion de envío

    $(".confirmSend").click(function () {
      var data = document.getElementsByName("checkOrden[]");

      var orderIds = [];

      for (var i = 0; i < data.length; i++) {
        if (data[i].checked) {
          orderIds.push(data[i].value);
        }
      }

      if (orderIds.length > 0) {
        Swal.fire({
          title: "¿Deseas confirmar para entrega?",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si, confirmar",
          cancelButtonText: "Cancelar",
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "admin-ajax.php", // Url to which the request is send
              type: "POST",
              data: {
                orderIds: orderIds,
                action: "fedex_shipping_intra_Chile_confirm_send",
              },
              beforeSend: function () {
                const Toast = Swal.mixin({
                  toast: true,
                  position: "top-end",
                  showConfirmButton: false,
                  timer: 1000,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                  },
                });

                Toast.fire({
                  icon: "info",
                  title: "Solicitando el servicio",
                  text: "Espere un momento...",
                });
              },

              success: function (data) {
                var parseData = JSON.parse(data);


                if (parseData.status == "OK") {
                  Swal.fire({
                    title: parseData.message,
                    text: "Deseas imprimir el manifiesto?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, imprimir!",
                  }).then((result) => {
                    if (result.isConfirmed) {
                      var pdfWindow = window.open("");
                      pdfWindow.document.write(
                        "<title>FedEx Shipping Manifest</title>"
                      );

                      pdfWindow.document.write(
                        "<embed width='100%' height='100%' src='data:application/pdf;base64, " +
                          encodeURI(parseData.manifestBase64) +
                          "#toolbar=1&navpanes=0&scrollbar=0'>"
                      );

                      location.reload();
                    }
                  });
                } else {
                  Swal.fire({
                    title: "Error",
                    icon: "error",
                    html: "<b>" + parseData.message + "</b>",
                  });
                }
              },
              completed: function () {},

              error: function (data) {
                console.log(data);
                Swal.fire({
                  title: "Error",
                  text: "Error al guardar los datos",
                  icon: "error",
                  confirmButtonText: "Cerrar",
                });
              },
            });
          }
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Debe seleccionar para confirmar el envío",
        });
      }
    });

    //Imprimir manifiesto de envío

    $(".printManifest").click(function () {
      $.ajax({
        url: "admin-ajax.php",
        type: "POST",
        data: {
          manifest: $(this).data("manifest"),
          action: "fedex_shipping_intra_Chile_print_manifest",
        },
        beforeSend: function () {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "info",
            title: "Imprimiendo manifiesto",
            text: "Espere un momento...",
          });
        },
        success: function (response) {
          var parseData = JSON.parse(response);


          var pdfWindow = window.open("");
          pdfWindow.document.write("<title>FedEx Shipping Manifest</title>");

          pdfWindow.document.write(
            "<embed width='100%' height='100%' src='data:application/pdf;base64, " +
              encodeURI(parseData) +
              "#toolbar=1&navpanes=0&scrollbar=0'>"
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    // Eliminar orden transporte

    $(document).on("click", ".delete_shipping", function () {
      var orderId = $(this).data("order");

      Swal.fire({
        title: "¿Deseas eliminar la orden: <b>" + orderId + "</b> ?",
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, borrar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: "admin-ajax.php",
            type: "POST",
            data: {
              orderId: orderId,
              action: "fedex_shipping_intra_Chile_delete_order",
            },

            beforeSend: function () {
              const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
              });

              Toast.fire({
                icon: "info",
                title: "Eliminando registros",
                text: "Espere un momento...",
              });
            },

            success: function (response) {
              var parseData = JSON.parse(response);

              parseData.forEach((element) => {

                if (element.respuestaAnularWebExpediciones.resultado == "OK") {
                  Swal.fire({
                    icon: "success",
                    title:
                      "La orden de transporte #<b>" +
                      element.respuestaAnularWebExpediciones.webExpedicion +
                      "</b> fue eliminada",
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: "Ok",
                    denyButtonText: `Cerrar`,
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                      location.reload();
                    } else if (result.isDenied) {
                      Swal.fire("Changes are not saved", "", "info");
                    }
                  });
                } else {
                  Swal.fire({
                    icon: "error",
                    title: "No pudo ser eliminada la orden de transporte",
                    text: "",
                    confirmButtonText: "Cerrar",
                  });
                }
              });
            },
          });
        }
      });
    });

    // Seguimiento de Envío

    $(document).on("click", ".trackShipment", function () {
      var orderId = $(this).data("order");

      $.ajax({
        url: "admin-ajax.php",
        type: "POST",
        data: {
          orderId: orderId,
          action: "fedex_shipping_intra_Chile_track_shipment",
        },
        beforeSend: function () {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
            },
          });

          Toast.fire({
            icon: "info",
            title: "Consultando envío",
            text: "Espere un momento...",
          });
        },
        success: function (response) {
          var parseData = JSON.parse(response);

          let windOpenTraking = null;


          windOpenTraking = window.open(
            "https://gtstnt.tntchile.cl/gtstnt/pub/clielocserv.seam?expedicion=" +
              parseData.masterTrackingNumber.masterTrackingNumber +
              "&cliente=" +
              parseData.accountNumber.accountNumber +
              ", _blank"
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    // Localizador de ciudad/comuna/pueblo BD

    let url = "admin-ajax.php";

    $("input.typeaheadLocation").typeahead({
      items: 80, //Cantidad de elementos mostrados en lista
      minChars: 0,
      source: function (query, process) {
        $.get(
          url,
          (data = {
            action: "fedex_shipping_intra_Chile_get_locations",
            query: query,
          }),

          function (data) {
            objects = [];
            labelCiudad = {};

            //Cliclo para llenar el autocomplete
            $.each(data, function (i, item) {
              var queryLabel = item.ciudad;
              var nameCiudad = item.ciudad;
              
              labelCiudad[queryLabel] = item;
              objects.push(queryLabel);
            });

            process(objects);
          },
          "json"
        );
      },

      updater: function (queryLabel) {
        var item = labelCiudad[queryLabel];
        var input_label = queryLabel;
        //$('#hiddeEmpresaid').val(item.empresa);
        $("#postalCodeRecipient").val(item.codigo);
        return input_label;
      },
    });
  });
})(jQuery);
