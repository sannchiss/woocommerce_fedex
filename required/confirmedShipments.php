<?php

class confirmedShipments{

    public function __construct(){

        global $wpdb;
        global $table_prefix;

        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;

        $this->table_name_confirmationshipping = $table_prefix . 'fedex_shipping_intra_CL_confirmationShipping';

    }

   public function index(){


    $select = $this->wpdb->get_results("SELECT * FROM " . $this->table_name_confirmationshipping, ARRAY_A);

    $retreats = array();

    if(count($select) >= 0){

        foreach ($select as $key => $value) {

            $retreats[$key] = array(
   
               'manifestNumber' => $value['manifestNumber'],
               'manifestBase64PDF' => $value['manifestBase64PDF'],
               'manifestDate' => $value['manifestDate'],
   
           );  
   
   
       }        

    }else{

        $retreats = array(
   
            'manifestNumber' => '',
            'manifestBase64PDF' => '',
            'manifestDate' => '',

        ); 
    }
    

 


    return $retreats;


   }



        
    }