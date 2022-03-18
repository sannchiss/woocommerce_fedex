<?php if (current_user_can('manage_options')) : #Condicional Alternativa 

?>

<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed bg-primary bg-gradient py-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                aria-controls="collapseOne">

                <div class="bg-primary bg-gradient text-wrap text-white" style="width: 20rem;">
                    <i class="fas fa-tasks"></i> <span>Configuración de Cuenta </span>
                </div>

            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">

                <div class="container">

                    <form id="configuration">

                        <div class="row">

                            <div class="col-md-12">

                                <div class="shadow p-3 mb-5 bg-white rounded">

                                    <div class="container-fluid" style="z-index:1">

                                        <div class="panel-heading">

                                            <h3 class="panel-title mb-3">Credenciales</h3>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="password" class="form-control" placeholder=""
                                                        id="accountNumber" name="accountNumber" minlength="9"
                                                        maxlength="30" value="" required>
                                                    <label for="accountNumber">Account Number</label>
                                                </div>

                                            </div>

                                        </div>

                                        <!--Meter Number--->

                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="password" class="form-control" placeholder=""
                                                        id="meterNumber" name="meterNumber" value="123456789"
                                                        minlength="9" maxlength="30" readonly>
                                                    <label for="meterNumber">Meter Number</label>
                                                </div>


                                            </div>

                                        </div>

                                        <!--wskey User Credential--->
                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="password" class="form-control" placeholder=""
                                                        id="wskeyUserCredential" name="wskeyUserCredential"
                                                        minlength="5" maxlength="50" required>
                                                    <label for="wskeyUserCredential">Wskey User Credential</label>
                                                </div>


                                            </div>

                                        </div>

                                        <!--wspassword User Credential--->
                                        <div class="row">

                                            <div class="col-md-5">

                                                <div class="form-floating mb-3">
                                                    <input type="password" class="form-control"
                                                        id="wskeyPasswordCredential" name="wskeyPasswordCredential"
                                                        minlength="5" maxlength="50" required>
                                                    <label for="wskeyPasswordCredential">Wspassword User
                                                        Credential</label>
                                                </div>

                                                <div id="html_bl"></div>

                                            </div>

                                        </div>

                                        <hr>

                                        <!--service Type--->
                                        <div class="row">

                                            <div class="panel-heading">

                                                <h3 class="panel-title mb-3">Servicios/Tipos de emblaje/Tipo
                                                    pagador/Impresión/Unidad de
                                                    medida</h3>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="serviceType" name="serviceType"
                                                        required aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="Expreso" selected>01-Expreso</option>
                                                        <option value="Expreso_Documento">02-Expreso Documento
                                                        </option>
                                                        <option value="Courier">03-Courier</option>
                                                        <option value="Sobre_Courier">04-Sobre Courier 12:00 dia
                                                            siguiente</option>
                                                        <option value="Expreso_Valorado">11-Expreso Valorado</option>
                                                        <option value="Valorado_Courier">12-Valorado Courier</option>

                                                    </select>
                                                    <label for="serviceType">Service Type</label>
                                                </div>
                                            </div>

                                            <!--Packaging Type--->
                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="packagingType" name="packagingType"
                                                        required aria-label="Floating label select example" disabled>
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="YOUR_PACKAGING" selected>YOUR_PACKAGING</option>
                                                        <option value="FEDEX_ENVELOPE">FEDEX_ENVELOPE</option>
                                                        <option value="FEDEX_PAK">FEDEX_PAK</option>
                                                        <option value="FEDEX_BOX">FEDEX_BOX</option>
                                                        <option value="FEDEX_TUBE">FEDEX_TUBE</option>
                                                        <option value="FEDEX_BOX_10">FEDEX_BOX_10</option>
                                                        <option value="FEDEX_BOX_25">FEDEX_BOX_25</option>
                                                    </select>
                                                    <label for="packagingType">Packaging Type</label>
                                                </div>
                                            </div>

                                        </div>

                                        <!--label Type--->
                                        <div class="row">

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="paymentType" name="paymentType"
                                                        required aria-label="Floating label select example" disabled>
                                                        <option selected value="">Search...</option>
                                                        <option value="SENDER" selected>SENDER</option>
                                                        <option value="RECIPIENT">RECIPIENT</option>
                                                        <option value="THIRD_PARTY">THIRD_PARTY</option>
                                                    </select>
                                                    <label for="paymentType">Payment Type</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="labelType" name="labelType" required
                                                        aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="PDF" selected>PDF</option>
                                                        <option value="PDF2">PDF2</option>
                                                        <option value="ZPL">ZPL</option>
                                                    </select>
                                                    <label for="labelType">label Type</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="measurementUnits"
                                                        name="measurementUnits" required
                                                        aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="KG/CM" selected>KG/CM</option>
                                                        <option value="LBS/IN">LBS/IN</option>
                                                    </select>
                                                    <label for="measurementUnits">Measurement Units</label>
                                                </div>

                                            </div>



                                        </div>

                                        <hr>

                                        <!--Dimensions volume--->
                                        <div class="row">

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="flagInsurance"
                                                    onclick="activateDimensions(this);" value="1" id="flagInsurance">
                                                <label class="form-check-label" for="flagInsurance">
                                                    Activar en centimetros(cm)
                                                </label>
                                            </div>

                                            <div class="panel-heading">


                                                <h3 class="panel-title mb-3">Dimensiones estandar bulto/paquete</h3>


                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" placeholder="" id="width"
                                                        placeholder="cm" name="width" minlength="1" maxlength="4"
                                                        readonly="readonly">
                                                    <label for="width">Width (Ancho)</label>
                                                </div>

                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" placeholder="" id="length"
                                                        placeholder="cm" name="length" minlength="1" maxlength="4"
                                                        readonly="readonly">
                                                    <label for="length">Length (Longitud)</label>
                                                </div>

                                            </div>



                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" placeholder="" id="height"
                                                        placeholder="cm" name="height" minlength="1" maxlength="4"
                                                        readonly="readonly">
                                                    <label for="height">Height (Altura)</label>
                                                </div>

                                            </div>


                                        </div>





                                        <hr>
                                        <!--Environment--->
                                        <div class="row">

                                            <div class="panel-heading">

                                                <h3 class="panel-title mb-3">Ambiente</h3>

                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="environment" name="environment"
                                                        required aria-label="Floating label select example">
                                                        <option selected disabled value="">Search...</option>
                                                        <option value="QA" selected>QA</option>
                                                        <option value="PRODUCTION">PRODUCTION</option>
                                                    </select>
                                                    <label for="environment">Environment</label>
                                                </div>

                                            </div>


                                            <hr>


                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="basic-addon3">Endpoint Tarifario</span>
                                                        <input type="text" class="form-control" id="endPointRate" name="endPointRate"
                                                            aria-describedby="basic-addon3">
                                                    </div>

                                                </div>


                                            </div>

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="basic-addon3">Endpoint creación de OT</span>
                                                        <input type="text" class="form-control" id="endPointShip" name="endPointShip"
                                                            aria-describedby="basic-addon3">
                                                    </div>

                                                </div>


                                            </div>

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="basic-addon3">Endpoint confirmación de OT</span>
                                                        <input type="text" class="form-control" id="endPointConfirmation" name="endPointConfirmation"
                                                            aria-describedby="basic-addon3">
                                                    </div>

                                                </div>


                                            </div>

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="basic-addon3">Endpoint impresión de OT</span>
                                                        <input type="text" class="form-control" id="endPointPrintLabel" name="endPointPrintLabel"
                                                            aria-describedby="basic-addon3">
                                                    </div>

                                                </div>


                                            </div>


                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="basic-addon3">Endpoint cancelación de OT</span>
                                                        <input type="text" class="form-control" id="endPointCancel" name="endPointCancel"
                                                            aria-describedby="basic-addon3">
                                                    </div>

                                                </div>


                                            </div>

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="basic-addon3">Endpoint impresión de manifiestos</span>
                                                        <input type="text" class="form-control" id="endPointPrintManifestPdf" name="endPointPrintManifestPdf"
                                                            aria-describedby="basic-addon3">
                                                    </div>

                                                </div>


                                            </div>


                                           






                                        </div>

                                    </div>

                                    <hr>


                                    <div class="footer">

                                        <div class="container">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="footer">
                                                        <button type="submit"
                                                            class="send_config btn btn-primary">Guardar</button>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>


                    </form>

                </div>

            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">

            <button class="accordion-button collapsed bg-primary bg-gradient py-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
                aria-controls="collapseTwo">

                <div class="bg-primary bg-gradient text-wrap text-white" style="width: 12rem;">
                    <i class="fas fa-tasks"></i> <span>Datos de Origen </span>
                </div>

            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">


                <div class="container-fluid">


                    <div class="row">

                        <div class="col-md-12">

                            <div class="shadow p-3 mb-5 bg-white rounded">


                                <div class="card-body">

                                    <form id="originShipper">


                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="personNameShipper"
                                                        name="personNameShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="personNameShipper">Person Name </label>
                                                </div>


                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-floating mb-3">
                                                    <input type="tel" class="form-control" id="phoneShipper"
                                                        name="phoneShipper" aria-label="Floating label example input">
                                                    <label for="phoneShipper">Phone </label>
                                                </div>


                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="companyNameShipper"
                                                        name="companyNameShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="companyNameShipper">Company Name</label>
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-floating mb-3">
                                                    <input type="email" class="form-control" id="emailShipper"
                                                        name="emailShipper" aria-label="Floating label example input">
                                                    <label for="emailShipper">Email</label>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="vatNumberShipper"
                                                        name="vatNumberShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="vatNumberShipper">Nif</label>
                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">

                                                    <select class="form-control" id="cityShipper" name="cityShipper"
                                                        aria-label="Floating label example input">

                                                        <option selected disabled value="">Search...</option>

                                                        <option value="ANCUD">ANCUD</option>
                                                        <option value="ANGOL">ANGOL</option>
                                                        <option value="ANTOFAGASTA">ANTOFAGASTA</option>
                                                        <option value="ARICA">ARICA</option>
                                                        <option value="CALAMA">CALAMA</option>
                                                        <option value="CASTRO">CASTRO</option>
                                                        <option value="CHILLAN">CHILLAN</option>
                                                        <option value="CONCEPCION">CONCEPCION</option>
                                                        <option value="COPIAPO">COPIAPO</option>
                                                        <option value="COQUIMBO">COQUIMBO</option>
                                                        <option value="CURICO">CURICO</option>
                                                        <option value="IQUIQUE">IQUIQUE</option>
                                                        <option value="LA SERENA">LA SERENA</option>
                                                        <option value="LOS ANDES">LOS ANDES</option>
                                                        <option value="LINARES">LINARES</option>
                                                        <option value="LOS ANGELES">LOS ANGELES</option>
                                                        <option value="LA UNION">LA UNION</option>
                                                        <option value="LOS VILOS">LOS VILOS</option>
                                                        <option value="OSORNO">OSORNO</option>
                                                        <option value="OVALLE">OVALLE</option>
                                                        <option value="PUERTO MONTT">PUERTO MONTT</option>
                                                        <option value="PUERTO NATALES">PUERTO NATALES</option>
                                                        <option value="PUNTA ARENAS">PUNTA ARENAS</option>
                                                        <option value="QUILLOTA">QUILLOTA</option>
                                                        <option value="RANCAGUA">RANCAGUA</option>
                                                        <option value="SAN ANTONIO">SAN ANTONIO</option>
                                                        <option value="SAN FELIPE">SAN FELIPE</option>
                                                        <option value="SAN FERNANDO">SAN FERNANDO</option>
                                                        <option value="SANTIAGO">SANTIAGO</option>
                                                        <option value="SANTIAGO RM">SANTIAGO RM</option>
                                                        <option value="TALCA">TALCA</option>
                                                        <option value="TEMUCO">TEMUCO</option>
                                                        <option value="TOCOPILLA">TOCOPILLA</option>
                                                        <option value="VALDIVIA">VALDIVIA</option>
                                                        <option value="VALLENAR">VALLENAR</option>
                                                        <option value="VALPARAISO">VALPARAISO</option>
                                                        <option value="VICHUQUEN">VICHUQUEN</option>
                                                        <option value="VICHUQUEN">VILLARRICA</option>
                                                        <option value="VINA DEL MAR">VINA DEL MAR</option>
                                                        <option value="VITACURA">VITACURA</option>
                                                        <option value="YUMBEL">YUMBEL</option>
                                                        <option value="YUNGAY">YUNGAY</option>

                                                    </select>

                                                    <label for="cityShipper">Location / City</label>
                                                </div>

                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">

                                                    <select class="form-control" id="stateOrProvinceCodeShipper"
                                                        name="stateOrProvinceCodeShipper"
                                                        aria-label="Floating label example input" disabled>
                                                        <option selected disabled value="">Search...</option>

                                                    </select>
                                                    <label for="stateOrProvinceCodeShipper">State</label>
                                                </div>

                                            </div>


                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="postalCodeShipper"
                                                        name="postalCodeShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="postalCodeShipper">Postal Code</label>
                                                </div>

                                            </div>

                                            <div class="col-md-1">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="countryCodeShipper"
                                                        name="countryCodeShipper" value="MX"
                                                        aria-label="Floating label example input" readonly>
                                                    <label for="countryCodeShipper">Country</label>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="addressLine1Shipper"
                                                        name="addressLine1Shipper"
                                                        aria-label="Floating label example input">
                                                    <label for="addressLine1Shipper">Address Line 1</label>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="addressLine2Shipper"
                                                        name="addressLine2Shipper"
                                                        aria-label="Floating label example input">
                                                    <label for="addressLine2Shipper">Address Line 2</label>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">


                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="taxIdShipper"
                                                        name="taxIdShipper" aria-label="Floating label example input">
                                                    <label for="taxIdShipper">Tax</label>
                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="ieShipper"
                                                        name="ieShipper" aria-label="Floating label example input">
                                                    <label for="ieShipper">Ie</label>
                                                </div>

                                            </div>


                                        </div>
                                        <hr>


                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="footer">
                                                    <button type="submit"
                                                        class="send_config btn btn-primary">Guardar</button>
                                                </div>

                                            </div>


                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>


</div>


<?php else : ?>
<p>
    No tienes acceso a esta sección
</p>
<?php endif; ?>

<script type="text/javascript">
function activateDimensions(obj) {

    if (obj.checked) {
        document.getElementById('width').readOnly = false;
        document.getElementById('length').readOnly = false;
        document.getElementById('height').readOnly = false;

    } else {
        document.getElementById('width').readOnly = true;
        document.getElementById('length').readOnly = true;
        document.getElementById('height').readOnly = true;
    }


}
</script>