<?php
/**
 * WHMCS-SupportPIN - Let your customers generate a support/phone pin to identify your customers faster, for example on the phone
 *
 * Copyright (c) 2021 Felix Brettnich
 *
 * This file is part of WHMCS-SupportPIN
 *
 * Licensed under GPL-3.0 (https://github.com/fbrettnich/WHMCS-SupportPIN/blob/main/LICENSE)
 */

use WHMCS\Database\Capsule;
use WHMCS\Module\AbstractWidget;

add_hook('AdminHomeWidgets', 1, function() {
    return new SupportPinWidget();
});

add_hook('ClientAreaPrimarySidebar', 1, function($primarySidebar) {

    if (!is_null($primarySidebar->getChild('Client Details'))) {

        $searchCustomerExists = Capsule::table('mod_supportpin')->where("customerid", "=", $_SESSION['uid'])->get();

        if (empty($searchCustomerExists)) {
            $supportPin = generateAvailablePin();

            try {
                Capsule::table('mod_supportpin')->insert(
                    [
                        'customerid' => $_SESSION['uid'],
                        'pin' => $supportPin,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]
                );
            } catch (\Exception $e) {
                echo "I couldn't create client support pin. {$e->getMessage()}";
            }
        } else {
            $customerInfo = Capsule::table('mod_supportpin')->where("customerid", "=", $_SESSION['uid'])->first();
            $supportPin = $customerInfo->pin;
        }

        $supportPinMenu = $primarySidebar->addChild('supportPinMenu',
            array(
                'name' => 'SupportPin',
                'label' => 'Support Pin',
                'uri' => 'index.php?m=supportpin',
                'order' => 1,
                'icon' => 'fas fa-key'
            )
        );

        // For Lagom Theme
        $supportPinMenu->setBodyHtml('
<span style="font-size: 35px; color: greenyellow;">' . $supportPin . '</span>
<span style="font-size: 20px;margin-left: 7px;"><a href="index.php?m=supportpin"><i class="fas fa-sync-alt"></i></a></span>
');

        // For default Themes
        /*$supportPinMenu->setBodyHtml('
<div style="text-align: center;">
<div style="font-size: 35px; color: greenyellow;">' . $supportPin . '</div>
<br>
<a href="index.php?m=supportpin" class="btn btn-success btn-sm"><i class="fas fa-sync-alt"></i></a>
</div>
');*/

    }
});

class SupportPinWidget extends AbstractWidget
{

    protected $title = 'SupportPin';
    protected $description = '';
    protected $weight = 60;
    protected $wrapper = false;
    protected $cache = false;

    public function getData() {
        return array();
    }

    public function generateOutput($data) {

        $adminlang_global_search = AdminLang::trans('global.search');

        return <<<EOF
<div class="panel panel-default" data-widget="SupportPinWidget">
    <div class="panel-heading">
        <div class="widget-tools">
            <a href="/admin/#" class="widget-minimise"><i class="fas fa-chevron-up"></i></a>
            <a href="/admin/#" class="widget-hide"><i class="fas fa-times"></i></a>
        </div>
        <h3 class="panel-title" style="touch-action: none;">Support PIN</h3>
    </div>
    <div class="panel-body">
        <div class="widget-content-padded">
            <div class="text-center">
                <form action="addonmodules.php?module=supportpin" method="post"  style="text-align: center;">
                    <div class="input-group input-group-sm">
                        <input type="number" name="searchsupportpin" class="form-control" required>
                        <div class="input-group-btn">
                            <input type="submit" class="btn btn-success btn-sm" value="{$adminlang_global_search}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
EOF;
    }
}

function generateAvailablePin() {
    $randomPin = rand(100000, 999999);

    $pinExists = Capsule::table('mod_supportpin')->where("pin", "=", $randomPin)->get();

    if(strlen($pinExists) > 2) {
        return generateAvailablePin();
    }

    return $randomPin;
}