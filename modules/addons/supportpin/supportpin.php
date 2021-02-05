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

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function supportpin_config() {
    return [
        'name' => 'Support PIN',
        'description' => 'Let your customers generate a support/phone pin to identify your customers faster, for example on the phone',
        'author' => 'Felix Brettnich',
        'language' => 'english',
        'version' => '1.0',
    ];
}

function supportpin_activate() {
    try {
        Capsule::schema()->create('mod_supportpin',
                function ($table) {
                    $table->increments('id');
                    $table->integer('customerid');
                    $table->integer('pin');
                    $table->timestamps();
                }
            );
        return [
            'status' => 'success',
            'description' => 'Support Pin has been successfully activated and can be used now.',
        ];
    } catch (\Exception $e) {
        return [
            'status' => "error",
            'description' => 'Unable to create mod_supportpin: ' . $e->getMessage(),
        ];
    }
}

function supportpin_deactivate() {
    try {
        Capsule::schema()->dropIfExists('mod_supportpin');
        return [
            'status' => 'success',
            'description' => 'Support Pin has been successfully disabled.',
        ];
    } catch (\Exception $e) {
        return [
            "status" => "error",
            "description" => "Unable to drop mod_supportpin: {$e->getMessage()}",
        ];
    }
}

function supportpin_clientarea($vars)
{

    $_lang = $vars['_lang'];
    $newSupportPin = supportpin_generateAvailablePin();
    $searchCustomerExists = Capsule::table('mod_supportpin')->where("customerid", "=", $_SESSION['uid'])->get();

    if(empty($searchCustomerExists)) {
        try {
            Capsule::table('mod_supportpin')->insert(
                [
                    'customerid' => $_SESSION['uid'],
                    'pin' => $newSupportPin,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]
            );
        } catch (\Exception $e) {
            echo "I couldn't create client support pin. {$e->getMessage()}";
        }
    }else{
        try {
            Capsule::table('mod_supportpin')
                ->where('customerid', $_SESSION['uid'])
                ->update(
                    [
                        'pin' => $newSupportPin,
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]
                );
        } catch (\Exception $e) {
            echo "I couldn't update client support pin. {$e->getMessage()}";
        }
    }

    return array(
        'pagetitle' => 'Support PIN',
        'breadcrumb' => array('index.php?m=supportpin' => 'Support PIN'),
        'templatefile' => 'index',
        'requirelogin' => true,
        'forcessl' => false,
        'vars' => array(
            'supportpin' => $newSupportPin,
            'lang_client_title' => $_lang['client_title'],
            'lang_client_info' => $_lang['client_info'],
            'lang_client_regenerate' => $_lang['client_regenerate'],
        ),
    );

}

function supportpin_sidebar($vars) {

    return '<span class="header"><img src="images/icons/search.png" class="absmiddle" width="16" height="16"> Support-PIN</span>
<ul class="menu">
    <form action="addonmodules.php?module=supportpin" method="post"  style="text-align: center;">
        <div class="input-group input-group-sm">
            <input type="number" name="searchsupportpin" class="form-control" required>
            <div class="input-group-btn">
                <input type="submit" class="btn btn-success btn-sm" value="' . AdminLang::trans('global.search') . '">
            </div>
        </div>
    </form>
</ul>';

}

function supportpin_output($vars) {

    $_lang = $vars['_lang'];

    if(isset($_POST['searchsupportpin'])) {
        $customerInfo = Capsule::table('mod_supportpin')->where("pin", "=", $_POST['searchsupportpin'])->first();
        header('Location: clientssummary.php?userid=' . $customerInfo->customerid);
        exit;
    }

    echo "
<h3>{$_lang['admin_title_activepins']}</h3>
<table id=\"supportpinCustomers\" style=\"width:100%\">
    <tr>
        <th style=\"width: 5%\">{$_lang['admin_table_id']}</th>
        <th style=\"width: 20%\">{$_lang['admin_table_firstname']}</th>
        <th style=\"width: 20%\">{$_lang['admin_table_lastname']}</th>
        <th style=\"width: 10%\">{$_lang['admin_table_pin']}</th>
        <th style=\"width: 25%\">{$_lang['admin_table_updated']}</th>
        <th style=\"width: 10%\"></th>
    </tr>
    ";

    foreach (Capsule::table('mod_supportpin')->whereRaw("`updated_at` > (NOW() - INTERVAL 2 DAY)")->orderBy("updated_at", "desc")->get() as $client) {
        $customerInfo = Capsule::table('tblclients')->where("id", "=", $client->customerid)->first();

        echo "
    <tr>
        <td>{$client->customerid}</td>
        <td>{$customerInfo->firstname}</td>
        <td>{$customerInfo->lastname}</td>
        <td>{$client->pin}</td>
        <td>{$client->updated_at}</td>
        <td style=\"text-align: center;\"><a href=\"clientssummary.php?userid={$client->customerid}\" class=\"btn btn-success\">{$_lang['admin_table_gocustomer']}</a></td>
    </tr>
";
    }

    echo "
</table>

<style>
#supportpinCustomers td, #supportpinCustomers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#supportpinCustomers tr:nth-child(even){
background-color: #f1f1f1;
}

#supportpinCustomers tr:hover {
background-color: #ddd;
}

#supportpinCustomers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #35ac3a;
  color: white;
}
</style>
";

}

function supportpin_generateAvailablePin() {
    $randomPin = rand(100000, 999999);

    $pinExists = Capsule::table('mod_supportpin')->where("pin", "=", $randomPin)->get();

    if(!empty($pinExists)) {
        return supportpin_generateAvailablePin();
    }

    return $randomPin;
}