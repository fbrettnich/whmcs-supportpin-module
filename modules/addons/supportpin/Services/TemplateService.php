<?php
/**
 * WHMCS-SupportPIN - Let your customers generate a support/phone pin to identify your customers faster, for example on the phone
 *
 * Copyright (c) 2021 Felix Brettnich
 * Copyright (c) 2021 All contributors
 *
 * This file is part of fbrettnich/whmcs-supportpin-module
 *
 * Licensed under GPL-3.0 (https://github.com/fbrettnich/whmcs-supportpin-module/blob/main/LICENSE)
 */
namespace Fbrettnich\WhmcsSupportpinModule\Services;

use WHMCS\Database\Capsule;

class TemplateService {




    public function template_index($vars){
        $TemplateData = [];

        $clients = Capsule::table('mod_supportpin')
                            ->whereRaw("`mod_supportpin`.`updated_at` > (NOW() - INTERVAL 2 DAY)")
                            ->join('tblclients', 'mod_supportpin.customerid', '=', 'tblclients.id')
                            ->orderBy("mod_supportpin.updated_at", "desc")
                            ->select('mod_supportpin.customerid', 'mod_supportpin.pin', 'mod_supportpin.updated_at', 'tblclients.firstname', 'tblclients.lastname')
                            ->get();

        $TemplateData += [ "clients" => $clients ];
        return $TemplateData;
    }


    public function handle_POST($POST){

        if(isset($POST['searchsupportpin'])) {
            $customerInfo = Capsule::table('mod_supportpin')->where("pin", "=", $POST['searchsupportpin'])->first();
            header('Location: clientssummary.php?userid=' . $customerInfo->customerid);
            exit;
        }

    }



    public function template_Client_index($vars){
        $client = Capsule::table('mod_supportpin')->where('customerid', $vars['clientID'])->get();

        if (count($client) > 0){
            $supportpin = $client[0]->pin;
        } else {
            $supportpin = $this->createNewPIN($vars['clientID']);
        }

        return ["supportpin" => $supportpin ];
    }


    public function template_RenewPIN($clientid){
        Capsule::table('mod_supportpin')->where('customerid', $clientid)->delete(); // Delete all old entries
        $NewPIN = $this->createNewPIN($clientid);
        return ["PIN" => $NewPIN ];
    }


    private function createNewPIN($clientid){
        $NewPIN = $this->supportpin_generateAvailablePin();
        try {
            Capsule::table('mod_supportpin')->insert([

                "customerid" => $clientid,
                "pin"        => $NewPIN,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        } catch (\Exception $e) { logActivity(json_encode($e)); return null;  } // Just return everything to the log if something goes wrong
        return $NewPIN;
    }


    private function supportpin_generateAvailablePin() {
        $randomPin = rand(100000, 999999);
    
        $pinExists = Capsule::table('mod_supportpin')->where("pin", "=", $randomPin)->get();
    
        if(strlen($pinExists) > 2) {
            return $this->supportpin_generateAvailablePin();
        }
    
        return $randomPin;
    }

}

