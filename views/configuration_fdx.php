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
                                                    <input type="password" class="form-control"
                                                        value=<?php echo ACCOUNT_NUMBER ?> id="accountNumber"
                                                        name="accountNumber" minlength="9" maxlength="30" value=""
                                                        required>
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
                                                    <input type="password" class="form-control"
                                                        value=<?php echo WS_KEY_USER_CREDENTIAL ?> placeholder=""
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
                                                        value=<?php echo WS_KEY_PASSWORD_CREDENTIAL ?> minlength="5"
                                                        maxlength="50" required>
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
                                                        required aria-label="Floating label select example" <option
                                                        value="">Search...</option>
                                                        <option value="Expreso"
                                                            <?php if(SERVICE_TYPE ==  "Expreso"){ echo "selected"; } ?>>
                                                            01-Expreso</option>
                                                        <option value="Expreso_Documento"
                                                            <?php if(SERVICE_TYPE ==  "Expreso_Documento"){ echo "selected"; } ?>>
                                                            02-Expreso Documento</option>
                                                        <option value="Courier"
                                                            <?php if(SERVICE_TYPE ==  "Courier"){ echo "selected"; } ?>>
                                                            03-Courier</option>
                                                        <option value="Sobre_Courier"
                                                            <?php if(SERVICE_TYPE ==  "Sobre_Courier"){ echo "selected"; } ?>>
                                                            04-Sobre Courier 12:00 dia siguiente</option>
                                                        <option value="Expreso_Valorado"
                                                            <?php if(SERVICE_TYPE ==  "Expreso_Valorado"){ echo "selected"; } ?>>
                                                            11-Expreso Valorado</option>
                                                        <option value="Valorado_Courier"
                                                            <?php if(SERVICE_TYPE ==  "Valorado_Courier"){ echo "selected"; } ?>>
                                                            12-Valorado Courier</option>

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
                                                        <option value="">Search...</option>
                                                        <option value="PDF"
                                                            <?php if(LABEL_TYPE ==  "PDF"){ echo "selected"; } ?>>PDF
                                                        </option>
                                                        <option value="PDF2"
                                                            <?php if(LABEL_TYPE ==  "PDF2"){ echo "selected"; } ?>>PDF2
                                                        </option>
                                                        <option value="ZPL"
                                                            <?php if(LABEL_TYPE ==  "ZPL"){ echo "selected"; } ?>>ZPL
                                                        </option>
                                                    </select>
                                                    <label for="labelType">label Type</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="measurementUnits"
                                                        name="measurementUnits" required
                                                        aria-label="Floating label select example">
                                                        <option value="">Search...</option>
                                                        <option value="KG/CM"
                                                            <?php if(MEASUREMENT_UNITS ==  "KG/CM"){ echo "selected"; } ?>>
                                                            KG/CM</option>
                                                        <option value="LBS/IN"
                                                            <?php if(MEASUREMENT_UNITS ==  "LBS/IN"){ echo "selected"; } ?>>
                                                            LBS/IN</option>
                                                    </select>
                                                    <label for="measurementUnits">Measurement Units</label>
                                                </div>

                                            </div>

                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="form-floating mb-3">
                                                <div class="panel-heading">

                                                    <h3 class="panel-title mb-3">Descuento Transporte</h3>




                                                </div>
                                            </div>

                                            <div class="col-md-5">

                                                <input type="range" class="form-range" id="formControlRange"
                                                    name="discount" min="0" max="100" value=<?php echo DISCOUNT; ?>
                                                    onChange="document.getElementById('rangeval').innerText = document.getElementById('formControlRange').value">

                                                <!--align center-->
                                                <div class="text-center">

                                                    <span class="badge bg-primary">
                                                        <span id="rangeval"><?php echo DISCOUNT; ?></span>
                                                        <span>%</span>
                                                    </span>

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
                                                        <option value="">Search...</option>
                                                        <option value="QA"
                                                            <?php if(ENVIRONMENT ==  "QA"){ echo "selected"; } ?>>QA
                                                        </option>
                                                        <option value="PRODUCTION"
                                                            <?php if(ENVIRONMENT ==  "PRODUCTION"){ echo "selected"; } ?>>
                                                            PRODUCTION</option>
                                                    </select>
                                                    <label for="environment">Environment</label>
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
                                                        value=<?php echo PERSON_NAME_SHIPPER; ?>
                                                        name="personNameShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="personNameShipper">Person Name </label>
                                                </div>


                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-floating mb-3">
                                                    <input type="tel" class="form-control" id="phoneShipper"
                                                        value=<?php echo PHONE_SHIPPER; ?> name="phoneShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="phoneShipper">Phone </label>
                                                </div>


                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="companyNameShipper"
                                                        value=<?php echo COMPANY_NAME_SHIPPER; ?>
                                                        name="companyNameShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="companyNameShipper">Company Name</label>
                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-floating mb-3">
                                                    <input type="email" class="form-control" id="emailShipper"
                                                        value=<?php echo EMAIL_SHIPPER; ?> name="emailShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="emailShipper">Email</label>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-md-2">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="vatNumberShipper"
                                                        value=<?php echo VAT_NUMBER_SHIPPER; ?> name="vatNumberShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="vatNumberShipper">Nif</label>
                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">

                                                    <select class="form-control" id="cityShipper" name="cityShipper"
                                                        aria-label="Floating label example input">

                                                        <option selected disabled value="">Search...</option>

                                                        <option value="ANCUD"
                                                            <?php if(CITY_SHIPPER == "ANCUD" ){ echo "selected"; } ?> >
                                                            ANCUD</option>
                                                        <option value="ANGOL">ANGOL</option>
                                                        <option value="ANTOFAGASTA"
                                                            <?php if(CITY_SHIPPER == "ANTOFAGASTA" ){ echo "selected"; } ?> >
                                                            ANTOFAGASTA</option>
                                                        <option value="ARICA"
                                                        <?php if(CITY_SHIPPER == "ARICA" ){ echo "selected"; } ?> >
                                                        ARICA</option>
                                                        <option value="CALAMA" <?php if(CITY_SHIPPER == "CALAMA" ){ echo "selected"; } ?> >CALAMA</option>
                                                        <option value="CASTRO" <?php if(CITY_SHIPPER == "CASTRO" ){ echo "selected"; } ?> >CASTRO</option>
                                                        <option value="CHILLAN" <?php if(CITY_SHIPPER == "CHILLAN" ){ echo "selected"; } ?> >CHILLAN</option>
                                                        <option value="CONCEPCION"
                                                            <?php if(CITY_SHIPPER == "CONCEPCION" ){ echo "selected"; } ?> >
                                                            CONCEPCION</option>
                                                        <option value="COPIAPO" <?php if(CITY_SHIPPER == "COPIAPO" ){ echo "selected"; } ?> >COPIAPO</option>
                                                        <option value="COQUIMBO" <?php if(CITY_SHIPPER == "COQUIMBO" ){ echo "selected"; } ?> >COQUIMBO</option>
                                                        <option value="CURICO" <?php if(CITY_SHIPPER == "CURICO" ){ echo "selected"; } ?> >CURICO</option>
                                                        <option value="IQUIQUE"
                                                            <?php if(CITY_SHIPPER == "IQUIQUE" ){ echo "selected"; } ?>>
                                                            IQUIQUE</option>
                                                        <option value="LA SERENA"
                                                            <?php if(CITY_SHIPPER == "LA SERENA" ){ echo "selected"; } ?>>
                                                            LA SERENA</option>
                                                        <option value="LOS ANDES" 
                                                        <?php if(CITY_SHIPPER == "LOS ANDES" ){ echo "selected"; } ?> > LOS ANDES</option>
                                                        <option value="LINARES" 
                                                        <?php if(CITY_SHIPPER == "LINARES" ){ echo "selected"; } ?> >LINARES</option>
                                                        <option value="LOS ANGELES"
                                                            <?php if(CITY_SHIPPER == "LOS ANGELES" ){ echo "selected"; } ?>>
                                                            LOS ANGELES</option>
                                                        <option value="LA UNION">LA UNION</option>
                                                        <option value="LOS VILOS">LOS VILOS</option>
                                                        <option value="OSORNO">OSORNO</option>
                                                        <option value="OVALLE">OVALLE</option>
                                                        <option value="PUERTO MONTT"
                                                            <?php if(CITY_SHIPPER == "PUERTO MONTT" ){ echo "selected"; } ?>>
                                                            PUERTO MONTT</option>
                                                        <option value="PUERTO NATALES"
                                                            <?php if(CITY_SHIPPER == "PUERTO NATALES" ){ echo "selected"; } ?>>
                                                            PUERTO NATALES</option>
                                                        <option value="PUNTA ARENAS"
                                                            <?php if(CITY_SHIPPER == "PUNTA ARENAS" ){ echo "selected"; } ?>>
                                                            PUNTA ARENAS</option>
                                                        <option value="QUILLOTA"
                                                            <?php if(CITY_SHIPPER == "QUILLOTA" ){ echo "selected"; } ?>>
                                                            QUILLOTA</option>
                                                        <option value="RANCAGUA"
                                                            <?php if(CITY_SHIPPER == "RANCAGUA" ){ echo "selected"; } ?>>
                                                            RANCAGUA</option>
                                                        <option value="SAN ANTONIO" <?php if(CITY_SHIPPER == "SAN ANTONIO" ){ echo "selected"; } ?>
                                                        >SAN ANTONIO</option>
                                                        <option value="SAN FELIPE"
                                                            <?php if(CITY_SHIPPER == "SAN FELIPE" ){ echo "selected"; } ?>>
                                                            SAN FELIPE</option>
                                                        <option value="SAN FERNANDO">SAN FERNANDO</option>
                                                        <option value="SANTIAGO"
                                                            <?php if(CITY_SHIPPER == "SANTIAGO" ){ echo "selected"; } ?> >
                                                            SANTIAGO</option>
                                                        <option value="SANTIAGO RM" 
                                                        <?php if(CITY_SHIPPER == "SANTIAGO RM" ){ echo "selected"; } ?>
                                                        >SANTIAGO RM</option>
                                                        <option value="TALCA" <?php if(CITY_SHIPPER == "TALCA" ){ echo "selected"; } ?>
                                                        >TALCA</option>
                                                        <option value="TEMUCO" <?php if(CITY_SHIPPER == "TEMUCO" ){ echo "selected"; } ?>
                                                        >TEMUCO</option>
                                                        <option value="TOCOPILLA" <?php if(CITY_SHIPPER == "TOCOPILLA" ){ echo "selected"; } ?>
                                                        >TOCOPILLA</option>
                                                        <option value="VALDIVIA" <?php if(CITY_SHIPPER == "VALDIVIA" ){ echo "selected"; } ?>
                                                        >VALDIVIA</option>
                                                        <option value="VALLENAR" <?php if(CITY_SHIPPER == "VALLENAR" ){ echo "selected"; } ?>
                                                        >VALLENAR</option>
                                                        <option value="VALPARAISO"
                                                            <?php if(CITY_SHIPPER == "VALPARAISO" ){ echo "selected"; } ?>>
                                                            VALPARAISO</option>
                                                        <option value="VICHUQUEN" <?php if(CITY_SHIPPER == "VICHUQUEN" ){ echo "selected"; } ?>
                                                        >VICHUQUEN</option>
                                                        <option value="VILLARRICA" <?php if(CITY_SHIPPER == "VILLARRICA" ){ echo "selected"; } ?>
                                                        >VILLARRICA</option>
                                                        <option value="VINA DEL MAR"
                                                            <?php if(CITY_SHIPPER == "VINA DEL MAR" ){ echo "selected"; } ?>>
                                                            VINA DEL MAR</option>
                                                        <option value="VITACURA"
                                                            <?php if(CITY_SHIPPER == "VITACURA" ){ echo "selected"; } ?>>
                                                            VITACURA</option>
                                                        <option value="YUMBEL"  <?php if(CITY_SHIPPER == "YUMBEL" ){ echo "selected"; } ?>
                                                        >YUMBEL</option>
                                                        <option value="YUNGAY" <?php if(CITY_SHIPPER == "YUNGAY" ){ echo "selected"; } ?>
                                                        >YUNGAY</option>

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
                                                        value=<?php echo POSTAL_CODE_SHIPPER; ?>
                                                        name="postalCodeShipper"
                                                        aria-label="Floating label example input">
                                                    <label for="postalCodeShipper">Postal Code</label>
                                                </div>

                                            </div>

                                            <div class="col-md-1">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="countryCodeShipper"
                                                        name="countryCodeShipper" value="CL"
                                                        aria-label="Floating label example input" readonly>
                                                    <label for="countryCodeShipper">Country</label>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <!--textarea-->
                                                    <textarea class="form-control" id="addressLine1Shipper"
                                                        name="addressLine1Shipper" rows="3"
                                                        aria-label="Floating label example input"
                                                        maxlength="150"><?php echo ADDRESS_LINE1_SHIPPER; ?></textarea>
                                                    <label for="addressLine1Shipper">Address Line 1</label>


                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-floating mb-3">
                                                    <!--textarea-->
                                                    <textarea class="form-control" id="addressLine2Shipper"
                                                        name="addressLine2Shipper" rows="3"
                                                        aria-label="Floating label example input"
                                                        maxlength="150"><?php echo ADDRESS_LINE2_SHIPPER; ?></textarea>
                                                    <label for="addressLine2Shipper">Address Line 2</label>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">


                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="taxIdShipper"
                                                        value=<?php echo TAX_ID_SHIPPER; ?> name="taxIdShipper"
                                                        aria-label="Floating label example input" readonly>
                                                    <label for="taxIdShipper">Tax</label>
                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="ieShipper"
                                                        value=<?php echo IE_SHIPPER; ?> name="ieShipper"
                                                        aria-label="Floating label example input" readonly>
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