<?php


trait configurationTrait {
    
    
    public function account() {

        global $wpdb;
        global $table_prefix;

       
        $sql = "SELECT * FROM " . $table_prefix . "fedex_shipping_intra_CL_configuration";
        $result = $wpdb->get_results($sql);

        foreach ($result as $key => $value) {
            
            $params['id'] = $value->id;
            $params['accountNumber'] = $value->accountNumber;
            $params['meterNumber'] = $value->meterNumber;
            $params['wskeyUserCredential'] = $value->wskeyUserCredential;
            $params['wskeyPasswordCredential'] = $value->wskeyPasswordCredential;
            $params['serviceType'] = $value->serviceType;
            $params['packagingType'] = $value->packagingType;
            $params['paymentType'] = $value->paymentType;
            $params['labelType'] = $value->labelType;
            $params['measurementUnits'] = $value->measurementUnits;
            $params['flagInsurance'] = $value->flagInsurance;
            $params['width'] = $value->width;
            $params['length'] = $value->length;
            $params['height'] = $value->height;
            $params['environment'] = $value->environment;
            $params['endPointRate'] = $value->endPointRate;
            $params['endPointShip'] = $value->endPointShip;
            $params['endPointConfirmation'] = $value->endPointConfirmation;
            $params['endPointPrintLabel'] = $value->endPointPrintLabel;
            $params['endPointPrintManifestPdf'] = $value->endPointPrintManifestPdf;

        }

        return $params;

    
    }

}