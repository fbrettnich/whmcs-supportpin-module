<?php
/**
 * WHMCS-SupportPIN - Let your customers generate a support/phone pin to identify your customers faster, for example on the phone
 *
 * Copyright (c) 2021 Felix Brettnich
 *
 * This file is part of fbrettnich/whmcs-supportpin-module
 *
 * Licensed under GPL-3.0 (https://github.com/fbrettnich/whmcs-supportpin-module/blob/main/LICENSE)
 */

use WHMCS\Database\Capsule;
use Fbrettnich\WhmcsSupportpinModule\Manager\TemplateManager;
use Fbrettnich\WhmcsSupportpinModule\Services\ResponseService;
use Fbrettnich\WhmcsSupportpinModule\Services\TemplateService;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

include_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'); 


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

function supportpin_clientarea($vars) {
    $clientid = $_SESSION['uid'];
    if ($clientid == null)
        return;

    $vars = array_merge($vars, [ "clientID" => $clientid ]);

    $page = isset($_GET['page']) ? $_GET['page'] : 'index';
    $CustomFunction = "template_Client_" . $page;
    $TPLManager = new TemplateManager(dirname(__FILE__), "client/index.tpl");
    $_lang = $vars['_lang'];
    $modulelink = $vars['modulelink'];

    if($page == "renew" && isset($_POST['PIN'])) // Generate a JSON response if the client just click renew in its dashboard to avoid a page reload
        (new ResponseService)->jsonResponse( (new TemplateService)->template_RenewPIN($clientid) );

    return array(
        'pagetitle' => 'Support PIN',
        'breadcrumb' => array('index.php?m=supportpin' => 'Support PIN'),
        'templatefile' => $TPLManager->getTemplate($page),
        'requirelogin' => true,
        'forcessl' => false,
        'vars' => array(
            'modulelink'    => $modulelink,
            'tplVars' => (new TemplateService)->$CustomFunction($vars),
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
    $smarty = new Smarty();
    $page = isset($_GET['page']) ? $_GET['page'] : 'index';
    $TPLManager = new TemplateManager(dirname(__FILE__));

    (new TemplateService)->handle_POST($_POST);

    
    //Assign the default variables to smarty
    $smarty->assign('modulelink', $vars['modulelink'] );
    $smarty->assign('tplPath', dirname(__FILE__) . '/templates' );
    $smarty->assign('currentPage', $page );
	$smarty->assign('addonlang', $_lang );
	$smarty->caching = false; // Debuggin
    $smarty->compile_dir = $GLOBALS['templates_compiledir'];
    $CustomFunction = "template_" . $page;
    $smarty->assign('tplVars', (new TemplateService)->$CustomFunction($vars)); // Assign template Variables

    //Load Template
	$smarty->display($TPLManager->getTemplate($page, true));

}

