<?php
/*
 * ###############################################################################
 * File: TemplateService.php
 * Project: Services
 * File Created: Thursday, 12th August 2021 12:21:16 pm
 * Author: Thomas Brinkmann (doyl@dsh.icu)
 * -----
 * Last Modified: Thursday, 12th August 2021 1:34:13 pm
 * Modified By: Thomas Brinkmann (doyl@dsh.icu>)
 * -----
 * Copyright 2021 - Thomas Brinkmann. All Rights Reserved.
 * -----
 * License Text 
 * Es ist Ihnen untersagt diese Software zu kopieren, manipulieren, verbreiten oder anderweitig ohne ausdrückliche Erlaubnis zu nutzen.
 * Sie dürfen ebenfalls nicht den Copyright Hinweis entfernen. 
 * 
 * It is prohibited to copy, manipulate, distribute or otherwise use this software without express permission.
 * You may also not remove the copyright notice. 
 * -----
 * ###############################################################################
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
        } catch (\Exception $e) { LogActivity(json_encode($e)); return null;  } // Just return everything to the log if something goes wrong
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

