<?php

class createTables
{


    public function __construct(){

        global $wpdb;
        global $table_prefix;
        global $confCharset;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->confCharset = $confCharset = $this->wpdb->get_charset_collate();

    }

    public function configuration(){
       
        $tabla = $this->table_prefix . 'fedex_shipping_intra_CL_configuration';

        $sql = "CREATE TABLE IF NOT EXISTS $tabla (
            id INT(11) NOT NULL AUTO_INCREMENT,
            accountNumber VARCHAR(150),
            meterNumber VARCHAR(150),
            wskeyUserCredential VARCHAR(150),
            wskeyPasswordCredential VARCHAR(150),
            serviceType VARCHAR(100),
            packagingType VARCHAR(100),
            paymentType VARCHAR(50),
            labelType VARCHAR(50),
            measurementUnits VARCHAR(100),
            flagInsurance INT(1),
            discount INT(20),
            environment VARCHAR(50),
            statusCreateOrder VARCHAR(30),
            statusConfirmOrder VARCHAR(30),
            endPointRate VARCHAR(150),
            endPointShip VARCHAR(150),
            endPointConfirmation VARCHAR(150),
            endPointPrintLabel VARCHAR(150),
            endPointCancel VARCHAR(150),
            endPointPrintManifestPdf VARCHAR(150),

            PRIMARY KEY (id)
        ) $this->confCharset;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        //VACIAR LA TABLA SI CONTIENEN DATOS
        $sql = "TRUNCATE " . $tabla;
        $this->wpdb->query($this->wpdb->prepare($sql));
        /******************************************* */

        // insert table
        $this->wpdb->insert(
            $tabla,
            array(
                'accountNumber' => '900063671',
                'meterNumber' => '123465789',
                'wskeyUserCredential' => 'SPEREZ',
                'wskeyPasswordCredential' => 'Home.2020',
                'serviceType' => 'Expreso',
                'packagingType' => '',
                'paymentType' => '',
                'labelType' => 'PDF',
                'measurementUnits' => 'KG/CM',
                'flagInsurance' => '',
                'discount' => 0,
                'environment' => 'PRODUCTION',
                'statusCreateOrder' => 'wc-procesado-fedex',
                'statusConfirmOrder' => 'wc-fedex',
                'endPointRate' => 'https://api.trinit.cl/fedex/v1/tarifario/',
                'endPointShip' => 'https://wsbeta.fedex.com/LAC/ServicesAPI/connector/cl/documentShip',
                'endPointConfirmation' => 'https://wsbeta.fedex.com/LAC/ServicesAPI/connector/cl/pickupManifest',
                'endPointPrintLabel' => 'https://wsbeta.fedex.com/LAC/ServicesAPI/connector/cl/labelService',
                'endPointCancel' => 'https://gtstnt.tntchile.cl/gtstnt/seam/resource/restv1/auth/anularWebExpediciones/anular',
                'endPointPrintManifestPdf' => 'https://gtstnt.tntchile.cl/gtstnt/seam/resource/restv1/auth/imprimirManifiestoService/imprimir',
            )
        );



    }

    public function originShipper(){

        $tabla = $this->table_prefix . 'fedex_shipping_intra_CL_originShipper';


        $sql = "CREATE TABLE IF NOT EXISTS $tabla (
            id INT(11) NOT NULL AUTO_INCREMENT,
            personNameShipper VARCHAR(150),
            phoneShipper VARCHAR(150),
            companyNameShipper VARCHAR(100),
            emailShipper VARCHAR(60),
            vatNumberShipper VARCHAR(50),
            cityShipper VARCHAR(50),
            stateOrProvinceCodeShipper VARCHAR(20),
            postalCodeShipper VARCHAR(20),
            countryCodeShipper VARCHAR(150),
            addressLine1Shipper VARCHAR(150),
            addressLine2Shipper VARCHAR(150),
            taxIdShipper VARCHAR(150),
            ieShipper VARCHAR(150),
           

            PRIMARY KEY (id)
        ) $this->confCharset;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        //VACIAR LA TABLA SI CONTIENEN DATOS
        $sql = "TRUNCATE " . $tabla;
        $this->wpdb->query($this->wpdb->prepare($sql));
        /******************************************* */

        // insert table
        $this->wpdb->insert(
            $tabla,
            array(
                'personNameShipper' => 'personNameShipper',
                'phoneShipper' => 'phoneShipper',
                'companyNameShipper' => 'companyNameShipper',
                'emailShipper' => 'emailShipper@none.cl',
                'vatNumberShipper' => 'vatNumberShipper',
                'cityShipper' => 'SANTIAGO',
                'stateOrProvinceCodeShipper' => '8320000',
                'postalCodeShipper' => 'postalCodeShipper',
                'countryCodeShipper' => 'countryCodeShipper',
                'addressLine1Shipper' => 'addressLine1Shipper',
                'addressLine2Shipper' => 'addressLine2Shipper',
                'taxIdShipper' => 'taxIdShipper',
                'ieShipper' => 'ieShipper',
            )
        );

    }


    public function responseShipping(){
            
            $tabla = $this->table_prefix . 'fedex_shipping_intra_CL_responseShipping';
    
            $sql = "CREATE TABLE IF NOT EXISTS $tabla (
                id INT(11) NOT NULL AUTO_INCREMENT,
                orderNumber INT(11),
                orderDate DATETIME,
                masterTrackingNumber VARCHAR(150),
                status VARCHAR(150),
                labelType VARCHAR(50),
                labelBase64IMG longtext,
                labelBase64PDF longtext,
                labelBase64PDF2 longtext,
                labelBase64ZPL longtext,
                labelBase64EPL longtext,

                PRIMARY KEY (id)
            ) $this->confCharset;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            
            //VACIAR LA TABLA SI CONTIENEN DATOS
            $sql = "TRUNCATE " . $tabla;
            $this->wpdb->query($this->wpdb->prepare($sql));
            /******************************************* */

    }

    public function confirmationShipping(){

        $tabla = $this->table_prefix . 'fedex_shipping_intra_CL_confirmationShipping';

        $sql = "CREATE TABLE IF NOT EXISTS $tabla (
            id INT(11) NOT NULL AUTO_INCREMENT,
            manifestNumber VARCHAR(150),
            manifestBase64PDF longtext,
            manifestDate DATETIME,
            
            PRIMARY KEY (id)
        ) $this->confCharset;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        //VACIAR LA TABLA SI CONTIENEN DATOS
        $sql = "TRUNCATE " . $tabla;
        $this->wpdb->query($this->wpdb->prepare($sql));
        /******************************************* */

        

    }

    public function orderDetail(){

        $tabla = $this->table_prefix . 'fedex_shipping_intra_CL_orderDetail';

        $sql = "CREATE TABLE IF NOT EXISTS $tabla (

            id INT(11) NOT NULL AUTO_INCREMENT,
            orderNumber INT(11),
            weight VARCHAR(50),
            weightUnits VARCHAR(50),
            length VARCHAR(50),
            width VARCHAR(50),
            height VARCHAR(50),
            dimensionUnits VARCHAR(50),
            productDescription VARCHAR(150),
            quantity VARCHAR(50),
            totalPrice VARCHAR(50),
            created_at DATETIME,

            PRIMARY KEY (id)
        ) $this->confCharset;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        //VACIAR LA TABLA SI CONTIENEN DATOS
        $sql = "TRUNCATE " . $tabla;
        $this->wpdb->query($this->wpdb->prepare($sql));
        /******************************************* */



    }


    public function cityOrComune()
    {

        $nombreTabla = $this->wpdb->prefix  . 'fedex_shipping_intra_CL_localidades';
        $query = "CREATE TABLE $nombreTabla (
                 id int(11) NOT NULL AUTO_INCREMENT,
                 ciudad varchar(120) NOT NULL,
                 codigo varchar(255) NOT NULL,
                 PRIMARY KEY (id),
                 UNIQUE INDEX (id)
            ) $this->confCharset ";

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($query);

        //VACIAR LA TABLA SI CONTIENEN DATOS
        $sql = "TRUNCATE " . $nombreTabla;
        $this->wpdb->query($this->wpdb->prepare($sql));
        /******************************************* */
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ALTO JAHUEL", 'codigo' => "9508101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHICUREO", 'codigo' => "9348115"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL PAICO", 'codigo' => "9818102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA DEHESA", 'codigo' => "3108101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LONQUEN", 'codigo' => "9568105"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MACUL", 'codigo' => "7810000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MALLOCO", 'codigo' => "9758103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NOS", 'codigo' => "8058107"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA ANA DE CHENA", 'codigo' => "9718102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA ROSA DE CHENA", 'codigo' => "9718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTIAGO CENTRO", 'codigo' => "8320000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LO BARNECHEA", 'codigo' => "7690000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "BOLLENAR", 'codigo' => "9588101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "POMAIRE", 'codigo' => "9588117"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUANGUE", 'codigo' => "9588130"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTIAGO", 'codigo' => "8320000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LO ESPEJO", 'codigo' => "9120000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LO PRADO", 'codigo' => "8980000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MAIPU", 'codigo' => "9250000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ÑUÑOA", 'codigo' => "7750000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PEDRO AGUIRRE CERDA", 'codigo' => "8460000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PEÑALOLEN", 'codigo' => "7910000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PROVIDENCIA", 'codigo' => "7500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUDAHUEL", 'codigo' => "9020000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILICURA", 'codigo' => "8700000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUINTA NORMAL", 'codigo' => "8500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RECOLETA", 'codigo' => "8420000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RENCA", 'codigo' => "8640000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN BERNARDO", 'codigo' => "8050000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN JOAQUIN", 'codigo' => "8940000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN MIGUEL", 'codigo' => "8900000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN RAMON", 'codigo' => "8860000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VITACURA", 'codigo' => "7630000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAS CONDES", 'codigo' => "7550000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA REINA", 'codigo' => "7850000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "BUIN", 'codigo' => "9500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CALERA DE TANGO", 'codigo' => "9560000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COLINA", 'codigo' => "9340000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL MONTE", 'codigo' => "9810000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ISLA DE MAIPO", 'codigo' => "9790000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAMPA", 'codigo' => "9380000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PADRE HURTADO", 'codigo' => "9710000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAINE", 'codigo' => "9540000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PEÑAFLOR", 'codigo' => "9750000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUENTE ALTO", 'codigo' => "8150000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TALAGANTE", 'codigo' => "9670000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CERRILLOS", 'codigo' => "9200000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LO BARNECHEA", 'codigo' => "7690000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MAIPO", 'codigo' => "95000001"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RINCONADA DE MAIPU", 'codigo' => "9250000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MELIPILLA", 'codigo' => "9580000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CERRO NAVIA", 'codigo' => "9080000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CONCHALI", 'codigo' => "8540000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL BOSQUE", 'codigo' => "8010000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ESTACION CENTRAL", 'codigo' => "9160000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUECHURABA", 'codigo' => "8580000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "INDEPENDENCIA", 'codigo' => "8380000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA CISTERNA", 'codigo' => "7970000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA FLORIDA", 'codigo' => "8240000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA GRANJA", 'codigo' => "8780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA PINTANA", 'codigo' => "8820000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CARMEN ALTO", 'codigo' => "8320001"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHOLQUI", 'codigo' => "8320002"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PABELLON", 'codigo' => "8320003"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CRUCE LAS ARAÑAS", 'codigo' => "9718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LONGO VILOS", 'codigo' => "9718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MALLARAUCO", 'codigo' => "9718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MARIA PINTO", 'codigo' => "9718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PIRQUE", 'codigo' => "9480000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN JOSE", 'codigo' => "9718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN JOSE DE MAIPO", 'codigo' => "9460000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN PEDRO", 'codigo' => "9718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ALHUE CBST", 'codigo' => "9650000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ISLA DE PASCUA CBST", 'codigo' => "2770000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN PEDRO DE MELIPILLA CBST", 'codigo' => "9660000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TIL TIL CBST", 'codigo' => "9420000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NUNOA", 'codigo' => "7750000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PENAFLOR", 'codigo' => "9750000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PENALOLEN", 'codigo' => "7910000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILIMARI", 'codigo' => "1940000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PICHIDANGUI", 'codigo' => "1940000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS VILOS", 'codigo' => "1940000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SALAMANCA", 'codigo' => "1950000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ILLAPEL", 'codigo' => "1930000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CANELA BAJA  CBST", 'codigo' => "1960000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CANELA  CBST", 'codigo' => "1960000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL PALQUI", 'codigo' => "1888106"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MONTE PATRIA", 'codigo' => "1880000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SOTAQUI", 'codigo' => "1848122"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COMBARBALA", 'codigo' => "1890000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUNITAQUI", 'codigo' => "1900000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "OVALLE", 'codigo' => "1840000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RIO HURTADO  CBST", 'codigo' => "1870000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PERALILLO   LA SERENA", 'codigo' => "1760000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "GUANAQUEROS", 'codigo' => "1788102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA HERRADURA", 'codigo' => "1788112"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAS TACAS", 'codigo' => "1780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MORRILLOS", 'codigo' => "1780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAIHUANO", 'codigo' => "1770000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO VELERO", 'codigo' => "1780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TONGOY", 'codigo' => "1788107"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TOTORALILLO", 'codigo' => "1788117"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA SERENA", 'codigo' => "1700000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COQUIMBO", 'codigo' => "1780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VICUÑA", 'codigo' => "1760000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ANDACOLLO", 'codigo' => "1750000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA HIGUERA  CBST", 'codigo' => "1740000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAIHUANO  CBST", 'codigo' => "1770000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUASCO", 'codigo' => "1640000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FREIRINA", 'codigo' => "1630000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VALLENAR", 'codigo' => "1610000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "BAHIA INGLESA", 'codigo' => "1578101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL SALVADOR", 'codigo' => "1500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "INCA DE ORO", 'codigo' => "1530000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAIPOTE", 'codigo' => "1538101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COPIAPO", 'codigo' => "1530000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHAÑARAL", 'codigo' => "1490000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TIERRA AMARILLA", 'codigo' => "1580000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CALDERA", 'codigo' => "1570000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "DIEGO DE ALMAGRO", 'codigo' => "1500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CERRO MORENO", 'codigo' => "1248101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA NEGRA", 'codigo' => "1138004"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TOCOPILLA", 'codigo' => "1340000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ANTOFAGASTA", 'codigo' => "1240000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MEJILLONES", 'codigo' => "1310000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MARIA ELENA", 'codigo' => "1360000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TALTAL  CBST", 'codigo' => "1300000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CALAMA", 'codigo' => "1390000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN PEDRO DE ATACAMA", 'codigo' => "1410000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ALTO HOSPICIO", 'codigo' => "1130000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "IQUIQUE", 'codigo' => "1100000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUARA CBST", 'codigo' => "1140000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PICA  CBST", 'codigo' => "1170000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "AEROPUERTO DIEGO ARACENA", 'codigo' => "1130000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FUERTE CONDELL", 'codigo' => "1130000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CAMIÑA  CBST", 'codigo' => "1150000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COLCHANE  CBST", 'codigo' => "1160000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "POZO ALMONTE  CBST", 'codigo' => "1180000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "AZAPA", 'codigo' => "1000000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ARICA", 'codigo' => "1000000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MAITENCILLO", 'codigo' => "2500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CACHAGUA", 'codigo' => "2500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURAUMA", 'codigo' => "2520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL BELLOTO", 'codigo' => "2438102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HORCON", 'codigo' => "2498102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PLACILLA VIÑA DEL MAR", 'codigo' => "2520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PLAYA ANCHA", 'codigo' => "2340000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RODELILLO", 'codigo' => "2520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TABOLANGO", 'codigo' => "2138104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VENTANA", 'codigo' => "2490000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN PEDRO DE LIMACHE", 'codigo' => "2240000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "BARRANCAS", 'codigo' => "2178104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ISLA NEGRA", 'codigo' => "2708102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAS BRISAS", 'codigo' => "2720000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAS CRUCES", 'codigo' => "2698101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LO ABARCA", 'codigo' => "2688102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LLO LLEO", 'codigo' => "2660000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MIRASOL", 'codigo' => "2718102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN SEBASTIAN", 'codigo' => "2688104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL QUISCO", 'codigo' => "2700000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ALGARROBO", 'codigo' => "2710000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CARTAGENA", 'codigo' => "2680000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA CRUZ", 'codigo' => "2280000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "OLMUE", 'codigo' => "2330000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LIMACHE", 'codigo' => "2240000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTO DOMINGO", 'codigo' => "2720000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL TABO", 'codigo' => "2690000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PEÑABLANCA", 'codigo' => "2520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURACAVI", 'codigo' => "9630000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILPUE", 'codigo' => "2430000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VILLA ALEMANA", 'codigo' => "6500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VALPARAISO", 'codigo' => "2340000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CONCON", 'codigo' => "2510000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAPUDO", 'codigo' => "2070000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUCHUNCAVI", 'codigo' => "2500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUINTERO", 'codigo' => "2490000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ZAPALLAR", 'codigo' => "2060000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CASABLANCA", 'codigo' => "2480000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN ANTONIO", 'codigo' => "2660000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILLOTA", 'codigo' => "2260000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "REÑACA", 'codigo' => "2528103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PEÑUELAS", 'codigo' => "2520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VIÑA DEL MAR", 'codigo' => "2520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VINA DEL MAR", 'codigo' => "2520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ISLA JUAN FERNANDEZ  CBST", 'codigo' => "2600000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CATAPILCO", 'codigo' => "2500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ARTIFICIO", 'codigo' => "2058102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL MELON", 'codigo' => "2308101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA CALERA", 'codigo' => "2290000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA LIGUA", 'codigo' => "2030000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MELON", 'codigo' => "2290000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LLAY LLAY", 'codigo' => "2220000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CABILDO", 'codigo' => "2050000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PETORCA", 'codigo' => "2040000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NOGALES", 'codigo' => "2300000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HIJUELAS", 'codigo' => "2310000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CALLE LARGA", 'codigo' => "2130000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CATEMU", 'codigo' => "2230000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PANQUEHUE", 'codigo' => "2210000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RINCONADA", 'codigo' => "2140000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN ESTEBAN", 'codigo' => "2120000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA MARIA", 'codigo' => "2200000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUTAENDO", 'codigo' => "2190000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS ANDES", 'codigo' => "2100000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN FELIPE", 'codigo' => "2170000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHINCOLCO", 'codigo' => "1398103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS MOLLES", 'codigo' => "1398103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PICHICUY", 'codigo' => "1398103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PETORCA  CBST", 'codigo' => "2040000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COPEQUEN", 'codigo' => "3018101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL OLIVAR", 'codigo' => "2528102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "GULTRO", 'codigo' => "2828102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LO MIRANDA", 'codigo' => "3028102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS LIRIOS", 'codigo' => "2828110"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "OLIVAR ALTO", 'codigo' => "2920000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "OLIVAR BAJO", 'codigo' => "2920000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUNTA CORTES", 'codigo' => "2890000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "OLIVAR", 'codigo' => "2920000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CODEGUA", 'codigo' => "2900000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COINCO", 'codigo' => "3010000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COLTAUCO", 'codigo' => "3000000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "DONIHUE", 'codigo' => "3020000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "GRANEROS", 'codigo' => "2880000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MACHALI", 'codigo' => "2910000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "REQUINOA", 'codigo' => "2930000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN FRANCISCO DE MOSTAZAL", 'codigo' => "2890000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TUNICHE", 'codigo' => "2880000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ANGOSTURA", 'codigo' => "2898101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUINTA DE TIL COCO", 'codigo' => "2960000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RANCAGUA", 'codigo' => "2820000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PEUMO", 'codigo' => "2990000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAS CABRAS", 'codigo' => "3030000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PICHIDEGUA", 'codigo' => "2980000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA COMPAÑIA", 'codigo' => "2888101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MOSTAZAL", 'codigo' => "2890000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LITUECHE", 'codigo' => "3240000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NAVIDAD", 'codigo' => "3230000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ROSARIO", 'codigo' => "2998105"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CUNACO", 'codigo' => "3118101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MARCHIGUE", 'codigo' => "2980000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PANIAHUE", 'codigo' => "3138112"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAREDONES", 'codigo' => "3270000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PELEQUEN", 'codigo' => "2958103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PERALILLO", 'codigo' => "3170000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN VICENTE DE TAGUA TAGUA", 'codigo' => "2970000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PLACILLA  SAN FERNANDO", 'codigo' => "3100000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NANCAGUA", 'codigo' => "3110000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN FERNANDO", 'codigo' => "3070000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PICHILEMU", 'codigo' => "3220000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHEPICA", 'codigo' => "3120000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHIMBARONGO", 'codigo' => "3110000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MALLOA", 'codigo' => "2950000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RENGO", 'codigo' => "2940000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA CRUZ", 'codigo' => "3130000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PALMILLA", 'codigo' => "3160000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN VICENTE", 'codigo' => "2970000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA ESTRELLA  CBST", 'codigo' => "3250000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOLOL  CBST", 'codigo' => "3140000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LITUECHE  CBST", 'codigo' => "3240000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MARCHIGUE  CBST", 'codigo' => "2980000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NAVIDAD  CBST", 'codigo' => "3230000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAREDONES  CBST", 'codigo' => "3270000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PICHILEMU  CBST", 'codigo' => "3220000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUMANQUE  CBST", 'codigo' => "3150000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUALAÑE", 'codigo' => "3400000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MOLINA", 'codigo' => "3380000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RAUCO", 'codigo' => "3430000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ROMERAL", 'codigo' => "3370000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAGRADA FAMILIA", 'codigo' => "3390000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TENO", 'codigo' => "3360000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CUREPTO", 'codigo' => "3570000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LONTUE", 'codigo' => "3390000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS NICHES", 'codigo' => "3348111"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SARMIENTO", 'codigo' => "3348117"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TUTUQUEN", 'codigo' => "3348818"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LICANTEN", 'codigo' => "3410000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURICO", 'codigo' => "3340000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CUREPTO  CBST", 'codigo' => "3570000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VICHUQUEN  CBST", 'codigo' => "3420000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MAULE", 'codigo' => "3530000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PENCAHUE", 'codigo' => "3550000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CONSTITUCION", 'codigo' => "3560000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN JAVIER", 'codigo' => "3660000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EMPEDRADO", 'codigo' => "3540000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VILLA ALEGRE", 'codigo' => "3650000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PELARCO", 'codigo' => "3500000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN RAFAEL", 'codigo' => "3490000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN CLEMENTE", 'codigo' => "3520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CUMPEO", 'codigo' => "3518102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUTU", 'codigo' => "3568106"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RIO CLARO", 'codigo' => "3510000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TALCA", 'codigo' => "3460000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EMPEDRADO  CBST", 'codigo' => "3540000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHANCO", 'codigo' => "3720000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CAUQUENES", 'codigo' => "3690000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PARRAL", 'codigo' => "3630000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PELLUHUE", 'codigo' => "3710000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RETIRO", 'codigo' => "3640000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COLBUN", 'codigo' => "3610000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LONGAVI", 'codigo' => "3620000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "YERBAS BUENAS", 'codigo' => "3600000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHANCOL", 'codigo' => "3580000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURANIPE", 'codigo' => "3718102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PANIMAVIDA", 'codigo' => "3618104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LINARES", 'codigo' => "3580000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "BULNES", 'codigo' => "3930000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN CARLOS", 'codigo' => "3840000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHILLAN VIEJO", 'codigo' => "3820000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COELEMU", 'codigo' => "3970000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NINHUE", 'codigo' => "4010000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN NICOLAS", 'codigo' => "4020000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COIHUECO", 'codigo' => "3870000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUIRIHUE", 'codigo' => "4000000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILLON", 'codigo' => "3940000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHILLAN", 'codigo' => "3780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ÑIQUEN CBST", 'codigo' => "3850000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COBQUECURA  CBST", 'codigo' => "3990000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "EL CARMEN  CBST", 'codigo' => "3900000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PEMUCO  CBST", 'codigo' => "3910000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PINTO  CBST", 'codigo' => "3880000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PORTEZUELO  CBST", 'codigo' => "3960000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN IGNACIO  CBST", 'codigo' => "3890000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA CLARA  CBST", 'codigo' => "3780001"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUIRIQUINA", 'codigo' => "3890000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RANQUIL  CBST", 'codigo' => "3950000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN FABIAN  CBST", 'codigo' => "3860000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TREHUACO  CBST", 'codigo' => "4000000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOTA", 'codigo' => "4210000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TOME", 'codigo' => "4160000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LEBU", 'codigo' => "4350000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CARAMPANGUE", 'codigo' => "4368101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FLORIDA", 'codigo' => "4170000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA JUANA", 'codigo' => "4230000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUALQUI", 'codigo' => "4180000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CORONEL", 'codigo' => "4190000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TALCAHUANO", 'codigo' => "4260000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ARAUCO", 'codigo' => "4360000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURANILAHUE", 'codigo' => "4370000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS ALAMOS", 'codigo' => "4380000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CANETE", 'codigo' => "4390000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PENCO", 'codigo' => "4150000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHIGUAYANTE", 'codigo' => "4100000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN PEDRO DE LA PAZ", 'codigo' => "4130000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUALPEN", 'codigo' => "4600000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COLCURA", 'codigo' => "4218101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LARAQUETE", 'codigo' => "4368103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LENGA", 'codigo' => "4030000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LIRQUEN", 'codigo' => "4158103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOTA ALTO", 'codigo' => "4210000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CONCEPCION", 'codigo' => "4030000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOTA BAJO", 'codigo' => "4210000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUALPENCILLO", 'codigo' => "4030000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TUBUL  CBST", 'codigo' => "4030001"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FLORIDA  CBST", 'codigo' => "4170000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA JUANA  CBST", 'codigo' => "4230000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TUCAPEL", 'codigo' => "4480000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NACIMIENTO", 'codigo' => "4550000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MULCHEN", 'codigo' => "4530000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILACO", 'codigo' => "4520000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAJA", 'codigo' => "4560000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN ROSENDO", 'codigo' => "4570000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA BARBARA", 'codigo' => "4510000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "YUMBEL", 'codigo' => "4580000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CABRERO", 'codigo' => "4470000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "YUNGAY", 'codigo' => "3920000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NEGRETE", 'codigo' => "4540000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILLECO", 'codigo' => "4440000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS ANGELES", 'codigo' => "4440000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COIHUE", 'codigo' => "5048023"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUEPIL", 'codigo' => "4488101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MONTEAGUILA", 'codigo' => "4470000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SALTO DEL LAJA", 'codigo' => "4448127"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SANTA FE", 'codigo' => "4440000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ALTO BIO BIO  CBST", 'codigo' => "4590000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ANTUCO  CBST", 'codigo' => "4490000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN ROSENDO  CBST", 'codigo' => "4570000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUILLECO  CBST", 'codigo' => "4440000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUREN", 'codigo' => "4750000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ANGOL", 'codigo' => "4650000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CONTULMO", 'codigo' => "4400000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TRAIGUEN", 'codigo' => "4730000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VICTORIA", 'codigo' => "4720000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ERCILLA", 'codigo' => "4710000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS SAUCES", 'codigo' => "4760000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COLLIPULLI", 'codigo' => "4680000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MININCO", 'codigo' => "4688104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RENAICO", 'codigo' => "4670000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "GALVARINO  CBST", 'codigo' => "5030000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LUMACO  CBST", 'codigo' => "4740000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURACAUTIN", 'codigo' => "4700000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PADRE LAS CASAS", 'codigo' => "4850000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAUTARO", 'codigo' => "4860000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CARAHUE", 'codigo' => "5010000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NUEVA IMPERIAL", 'codigo' => "5020000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PITRUFQUEN", 'codigo' => "4950000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FREIRE", 'codigo' => "4940000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "GORBEA", 'codigo' => "4960000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CAJON", 'codigo' => "4788103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LABRANZA", 'codigo' => "4788104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PILLALELBUN", 'codigo' => "4780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TEMUCO", 'codigo' => "4780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUEPE", 'codigo' => "4948110"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHOLCHOL  CBST", 'codigo' => "5040000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CUNCO  CBST", 'codigo' => "4890000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LONQUIMAY  CBST", 'codigo' => "4690000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MELIPEUCO  CBST", 'codigo' => "4900000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PERQUENCO  CBST", 'codigo' => "4870000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO SAAVEDRA  CBST", 'codigo' => "5000000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TEODORO SCHMIDT  CBST", 'codigo' => "4990000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TOLTEN  CBST", 'codigo' => "4980000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VILCUN  CBST", 'codigo' => "4880000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "TIRUA  CBST", 'codigo' => "4410000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LONCOCHE", 'codigo' => "4970000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LANCO", 'codigo' => "5160000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PANGUIPULLI", 'codigo' => "5210000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LICAN RAY", 'codigo' => "4930000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUCON", 'codigo' => "4920000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VILLARRICA", 'codigo' => "4930000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MALALHUE", 'codigo' => "5168101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURARREHUE", 'codigo' => "4910000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURARREHUE  CBST", 'codigo' => "4910000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MAFIL", 'codigo' => "5200000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FUTRONO", 'codigo' => "5180000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CORRAL", 'codigo' => "5190000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS LAGOS", 'codigo' => "5170000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PAILLACO", 'codigo' => "5230000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "VALDIVIA", 'codigo' => "5090000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CIRUELOS", 'codigo' => "5158101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS MOLINOS", 'codigo' => "5098102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NIEBLA", 'codigo' => "5098103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PICHOY", 'codigo' => "5158107"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "REUMEN", 'codigo' => "5238103"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN JOSE DE LA MARIQUINA", 'codigo' => "5158109"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CORRAL  CBST", 'codigo' => "5190000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUYEHUE", 'codigo' => "5360000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PURRANQUE", 'codigo' => "5380000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RIO NEGRO", 'codigo' => "5390000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN PABLO LA UNION", 'codigo' => "5220000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "RIO BUENO", 'codigo' => "5220000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAGO RANCO", 'codigo' => "5250000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LA UNION", 'codigo' => "5220000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "OSORNO", 'codigo' => "5290000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ENTRE LAGOS", 'codigo' => "5368102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO OCTAY  CBST", 'codigo' => "5370000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN JUAN DE LA COSTA  CBST", 'codigo' => "5400000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ENTRE LAGOS  CBST", 'codigo' => "5368102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN PABLO LA UNION  CBST", 'codigo' => "5220000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LAGO RANCO  CBST", 'codigo' => "5250000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO VARAS", 'codigo' => "5550000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FRESIA", 'codigo' => "5600000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LOS MUERMOS", 'codigo' => "5590000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "MAULLIN", 'codigo' => "5580000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CALBUCO", 'codigo' => "5570000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "LLANQUIHUE", 'codigo' => "5610000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FRUTILLAR", 'codigo' => "5620000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO MONTT", 'codigo' => "5480000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ALERCE", 'codigo' => "5488101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHAMIZA", 'codigo' => "5488104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHINQUIHUE", 'codigo' => "5488105"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "NUEVA BRAUNAU", 'codigo' => "5558104"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PARGUA", 'codigo' => "5608101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PIEDRA AZUL", 'codigo' => "5488116"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HORNOPIREN  CBST", 'codigo' => "5851000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "HUALAIHUE CBST", 'codigo' => "5860000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ALTO PALENA  CBST", 'codigo' => "5880000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHAITEN  CBST", 'codigo' => "5850000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COCHAMO  CBST", 'codigo' => "5560000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "FUTALEUFU  CBST", 'codigo' => "5870000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHACAO", 'codigo' => "5718101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUEMCHI", 'codigo' => "5720000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUELLON", 'codigo' => "5790000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURACO DE VELEZ", 'codigo' => "5740000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "DALCAHUE", 'codigo' => "5730000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHONCHI", 'codigo' => "5770000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ANCUD", 'codigo' => "5710000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CASTRO", 'codigo' => "5700000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ACHAO", 'codigo' => "5758101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUQUELDON  CBST", 'codigo' => "5760000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUEILEN  CBST", 'codigo' => "5780000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "ACHAO  CBST", 'codigo' => "5758101"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CURACO DE VELEZ  CBST", 'codigo' => "5740000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "QUINCHAO  CBST", 'codigo' => "5750000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COYHAIQUE", 'codigo' => "5950000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO AYSEN", 'codigo' => "6008102"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "CHILE CHICO CBST", 'codigo' => "6050000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "COCHRANE CBST", 'codigo' => "6100000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO CISNES CBST", 'codigo' => "6105000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUNTA ARENAS", 'codigo' => "6200000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PUERTO NATALES", 'codigo' => "6160000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PORVENIR CBST", 'codigo' => "6300000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "PRIMAVERA CBST", 'codigo' => "6310000"));
        $this->wpdb->insert($nombreTabla, array('ciudad' => "SAN GREGORIO CBST", 'codigo' => "6260000"));


        return true;
    }




}