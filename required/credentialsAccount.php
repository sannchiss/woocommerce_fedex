<?php

class credentialsAccount {


    public function getDataAccount(){

        global $wpdb;
        global $table_prefix;


        //select with join table
        $sql = "SELECT * FROM ".$table_prefix."fedex_shipping_intra_CL_configuration 
        a INNER JOIN ".$table_prefix."fedex_shipping_intra_CL_originShipper at ON a.id = at.id";
       
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
            $params['discount'] = $value->discount;
            $params['environment'] = $value->environment;
            $params['endPointRate'] = $value->endPointRate;
            $params['endPointShip'] = $value->endPointShip;
            $params['endPointConfirmation'] = $value->endPointConfirmation;
            $params['endPointPrintLabel'] = $value->endPointPrintLabel;
            $params['endPointCancel'] = $value->endPointCancel;
            $params['endPointPrintManifestPdf'] = $value->endPointPrintManifestPdf;
            $params['personNameShipper'] = $value->personNameShipper;
            $params['phoneShipper'] = $value->phoneShipper;
            $params['companyNameShipper'] = $value->companyNameShipper;
            $params['emailShipper'] = $value->emailShipper;
            $params['vatNumberShipper'] = $value->vatNumberShipper;
            $params['cityShipper'] = $value->cityShipper;
            $params['stateOrProvinceCodeShipper'] = $value->stateOrProvinceCodeShipper;
            $params['postalCodeShipper'] = $value->postalCodeShipper;
            $params['countryCodeShipper'] = $value->countryCodeShipper;
            $params['addressLine1Shipper'] = $value->addressLine1Shipper;
            $params['addressLine2Shipper'] = $value->addressLine2Shipper;
            $params['taxIdShipper'] = $value->taxIdShipper;
            $params['ieShipper'] = $value->ieShipper;

        }

        return $params;


    }


}