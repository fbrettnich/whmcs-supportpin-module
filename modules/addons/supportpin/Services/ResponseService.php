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
