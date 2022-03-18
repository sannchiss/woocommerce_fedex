<div class="modal fade" id="modal-order" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Gestor de envío</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="orderSend" class="row g-3 needs-validation" novalidate>

                    <div class="container-fluid">

                        <div class="row">

                            <div class="card bg-light mb-3">

                                <div class="card-header mb-3">

                                    <h4 class="card-title">Detalle de la orden</h4>

                                </div>

                                <div class="row">

                                    <div class="col-md-3">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder="" id="orderNumber"
                                                name="orderNumber" minlength="10" maxlength="20" readonly>
                                            <label for="orderNumber">Order Number</label>
                                        </div>


                                    </div>

                                    <div class="col-md-3">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder="" id="orderDate"
                                                name="orderDate" readonly>
                                            <label for="orderDate">Order Date</label>
                                        </div>


                                    </div>


                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="personNameRecipient" name="personNameRecipient" readonly>
                                            <label for="personNameRecipient">Person name</label>
                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="phoneNumberRecipient" name="phoneNumberRecipient" required>
                                            <label for="phoneNumberRecipient">Phone Number</label>
                                            <div class="valid-feedback">
                                                    Correcto!
                                             </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="companyNameRecipient" name="companyNameRecipient" readonly>
                                            <label for="companyNameRecipient">Company Name/ Person</label>
                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="vatNumberRecipient" name="vatNumberRecipient">
                                            <label for="vatNumberRecipient">Nif</label>
                                            <div class="valid-feedback">
                                                    Correcto!
                                             </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" placeholder="" id="emailRecipient"
                                                name="emailRecipient" readonly>
                                            <label for="emailRecipient">Email</label>
                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" placeholder="" id="notesRecipient" rows="3"
                                                cols="20" name="notesRecipient" maxlength="20" readonly></textarea>
                                            <label for="notesRecipient">Customer Note</label>
                                        </div>

                                    </div>

                                </div>




                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="typeaheadLocation form-control" placeholder="" id="cityRecipient"
                                                name="cityRecipient" autocomplete="new-text">
                                            <label for="cityRecipient">City</label>
                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="stateOrProvinceCodeRecipient" name="stateOrProvinceCodeRecipient"
                                                readonly>
                                            <label for="stateOrProvinceCodeRecipient">State Code</label>
                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="postalCodeRecipient" name="postalCodeRecipient" readonly>
                                            <label for="postalCodeRecipient">Postal Code</label>
                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="countryCodeRecipient" name="countryCodeRecipient" readonly>
                                            <label for="countryCodeRecipient">Country Code</label>
                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="streetLine1Recipient" name="streetLine1Recipient" maxlength="40" value="" required>
                                            <label for="streetLine1Recipient">Street Line 1</label>
                                            <!-- <div class="valid-feedback">
                                                Correcto!
                                            </div> -->

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" placeholder=""
                                                id="streetLine2Recipient" name="streetLine2Recipient" maxlength="40" value="">
                                            <label for="streetLine2Recipient">Street Line 2</label>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <hr>

                            <!-- Detalle de la orden servicio/peso/volumen -->
                            <div class="row">


                                <div class="card bg-light mb-3">

                                    <div class="card-header mb-3">

                                        <h4 class="card-title">Caracteristicas de Orden</h4>

                                    </div>


                                    <!--service Type--->
                                    <div class="row">

                                        <div class="panel-heading">

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
                                                        <option value="Sobre_Courier">04-Sobre Courier 12:00 dia siguiente</option>
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
                                                <select class="form-select" id="paymentType" name="paymentType" required
                                                    aria-label="Floating label select example" disabled>
                                                    <option selected disabled value="">Search...</option>
                                                    <option value="SENDER" selected>SENDER</option>
                                                    <option value="RECIPIENT">RECIPIENT</option>
                                                    <option value="THIRD_PARTY">THIRD_PARTY</option>
                                                </select>
                                                <label for="paymentType">Payment Type</label>
                                            </div>
                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">

                                                <input type="text" class="form-control" placeholder="" id="labelType"
                                                    name="labelType" readonly>
                                                <label for="labelType">label Type</label>

                                            </div>
                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="measurementUnits"
                                                    name="measurementUnits" required disabled aria-label="Floating label select example"
                                                    aria-label="Floating label select example">
                                                    <option selected disabled value="">Search...</option>
                                                    <option value="KG/CM" selected>KG/CM</option>
                                                    <option value="LBS/IN">LBS/IN</option>
                                                </select>
                                                <label for="measurementUnits">Measurement Units</label>
                                            </div>

                                        </div>


                                    </div>

                                    <!--Peso--->
                                    <div class="row">


                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder=""
                                                    id="numberOfPieces" name="numberOfPieces" value="" readonly >
                                                <label for="numberOfPieces">Quantity Pieces</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder=""
                                                    id="packages" name="packages" value="1" >
                                                <label for="packages">Packages</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>

                                     </div>
                                     
                                     <div class="row">

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder="" id="weight"
                                                    name="weight" value="1" >
                                                <label for="weight">Weight</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder="" id="weightUnits"
                                                    name="weightUnits" value="" readonly>
                                                <label for="weightUnits">Weight Units</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder="" id="length"
                                                    name="length" value="" readonly>
                                                <label for="length">Length</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder="" id="width"
                                                    name="width" value="1" readonly>
                                                <label for="width">Width</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder="" id="height"
                                                    name="height" value="1" readonly>
                                                <label for="height">Height</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>

                                        </div>

                                        <div class="row">

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder="" id="volume"
                                                    name="volume" value="" maxlength="5"  >
                                                <label for="volume">Volume</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>


                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" placeholder=""
                                                    id="dimensionUnits" name="dimensionUnits"
                                                    value="<?php echo get_option('woocommerce_dimension_unit'); ?>"
                                                    readonly>
                                                <label for="dimensionUnits">Dimension Units</label>
                                                <div class="valid-feedback">
                                                    Correcto!
                                                </div>
                                            </div>

                                        </div>


                                    </div>

                                </div>
                            </div>

                            <hr>

                            <!-- Inicio seccion acordion -->

                            <div class="row">

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed bg-primary bg-gradient py-3"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapseOne" aria-expanded="false"
                                                aria-controls="flush-collapseOne">

                                                <div class="bg-primary bg-gradient text-wrap text-white"
                                                    style="width: 12rem;">
                                                    <i class="fas fa-tasks"></i> <span>Datos de origen </span>
                                                </div>

                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                            <!-- Inicio content -->
                                            <div class="row">

                                                <div class="card bg-light md-3">

                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="card-body">

                                                                <div class="row">

                                                                    <div class="col-md-6">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="personNameShipper"
                                                                                name="personNameShipper"
                                                                                aria-label="Floating label example input"
                                                                                readonly>
                                                                            <label for="personNameShipper">Person Name
                                                                            </label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                                    <div class="col-md-4">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="tel" class="form-control"
                                                                                id="phoneShipper" name="phoneShipper"
                                                                                aria-label="Floating label example input"
                                                                                readonly>
                                                                            <label for="phoneShipper">Phone </label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                </div>

                                                                <div class="row">

                                                                    <div class="col-md-6">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="companyNameShipper"
                                                                                name="companyNameShipper"
                                                                                aria-label="Floating label example input"
                                                                                readonly>
                                                                            <label for="companyNameShipper">Company
                                                                                Name</label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-4">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="email" class="form-control"
                                                                                id="emailShipper" name="emailShipper"
                                                                                aria-label="Floating label example input" readonly>
                                                                            <label for="emailShipper">Email</label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                                <div class="row">

                                                                    <div class="col-md-2">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="vatNumberShipper"
                                                                                name="vatNumberShipper"
                                                                                aria-label="Floating label example input" readonly>
                                                                            <label for="vatNumberShipper">Nif</label>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-3">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="cityShipper" name="cityShipper"
                                                                                aria-label="Floating label example input"
                                                                                readonly>
                                                                            <label for="cityShipper">City</label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-2">

                                                                        <div class="form-floating mb-3">
                                                                            <select class="form-control"
                                                                                id="stateOrProvinceCodeShipper"
                                                                                name="stateOrProvinceCodeShipper"
                                                                                aria-label="Floating label example input"
                                                                                readonly>
                                                                                <option selected disabled value="">
                                                                                    Search...</option>
                                                                                <option value="DF">Distrito Federal
                                                                                </option>
                                                                                <option value="AG">Aguascalientes
                                                                                </option>
                                                                                <option value="BC">Baja California
                                                                                </option>
                                                                                <option value="BS">Baja California Sur
                                                                                </option>
                                                                                <option value="CM">Campeche</option>
                                                                                <option value="CH">Chihuahua</option>
                                                                                <option value="CS">Chiapas</option>
                                                                                <option value="CO">Coahuila</option>
                                                                                <option value="CL">Colima</option>
                                                                                <option value="DG">Durango</option>
                                                                                <option value="GR">Guerrero</option>
                                                                                <option value="GT">Guanajuato</option>
                                                                                <option value="HG">Hidalgo</option>
                                                                                <option value="JA">Jalisco</option>
                                                                                <option value="MX">Mexico</option>
                                                                                <option value="MI">Michoacán</option>
                                                                                <option value="MO">Morelos</option>
                                                                                <option value="NA">Nayarit</option>
                                                                                <option value="NL">Nuevo Leon</option>
                                                                                <option value="OA">Oaxaca</option>
                                                                                <option value="PU">Puebla</option>
                                                                                <option value="QT">Querétaro</option>
                                                                                <option value="QR">Quintana Roo</option>
                                                                                <option value="SI">Sinaloa</option>
                                                                                <option value="SL">San Luis Potosí
                                                                                </option>
                                                                                <option value="SO">Sonora</option>
                                                                                <option value="TB">Tabasco</option>
                                                                                <option value="TM">Tamaulipas</option>
                                                                                <option value="TL">Tlaxcala</option>
                                                                                <option value="VE">Veracruz</option>
                                                                                <option value="YU">Yucatan</option>
                                                                                <option value="ZA">Zacatecas</option>
                                                                            </select>
                                                                            <label
                                                                                for="stateOrProvinceCodeShipper">State</label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                    <div class="col-md-2">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="postalCodeShipper"
                                                                                name="postalCodeShipper"
                                                                                aria-label="Floating label example input"
                                                                                readonly>
                                                                            <label for="postalCodeShipper">Postal
                                                                                Code</label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-2">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="countryCodeShipper"
                                                                                name="countryCodeShipper"
                                                                                readonly
                                                                                aria-label="Floating label example input">
                                                                            <label
                                                                                for="countryCodeShipper">Country</label>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <div class="row">

                                                                    <div class="col-md-6">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="addressLine1Shipper"
                                                                                name="addressLine1Shipper"
                                                                                aria-label="Floating label example input" readonly>
                                                                            <label for="addressLine1Shipper"
                                                                            >Address
                                                                                Line 1</label>
                                                                            <div class="valid-feedback">
                                                                                Correcto!
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-6">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="addressLine2Shipper"
                                                                                name="addressLine2Shipper"
                                                                                aria-label="Floating label example input" readonly>
                                                                            <label for="addressLine2Shipper">Address
                                                                                Line 2</label>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <div class="row">


                                                                    <div class="col-md-3">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="taxIdShipper" name="taxIdShipper"
                                                                                aria-label="Floating label example input" readonly>
                                                                            <label for="taxIdShipper">Tax</label>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-3">

                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="ieShipper" name="ieShipper"
                                                                                aria-label="Floating label example input" readonly>
                                                                            <label for="ieShipper">Ie</label>
                                                                        </div>

                                                                    </div>


                                                                </div>


                                                            </div>



                                                        </div>

                                                    </div>


                                                </div>



                                            </div>
                                            <!-- Fin content -->


                                        </div>
                                    </div>
                                </div>


                            </div>

                            <!-- Fin seccion acordion -->




                        </div>





                    </div>                    

                    <div class="modal-footer fixed-bottom bg-secondary">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Generar Envío</button>
                    </div>                   

                </form>


            </div>

        </div>
    </div>
</div>