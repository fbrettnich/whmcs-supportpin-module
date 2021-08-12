<?php
/*
 * ###############################################################################
 * File: ResponseService.php
 * Project: services
 * File Created: Thursday, 12th August 2021 12:14:24 pm
 * Author: Thomas Brinkmann (doyl@dsh.icu)
 * -----
 * Last Modified: Thursday, 12th August 2021 1:37:36 pm
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

class ResponseService {

    public function __construct() {}


    /** Terminates the WHMCS Response and sends a plain JSON Response
     * @param array $data
     * 
     * @return json
     */
    public function jsonResponse(array $data){
        $response = new \WHMCS\Http\JsonResponse((array)$data);
        $response->send();
        \WHMCS\Terminus::getInstance()->doExit();
    }

}
