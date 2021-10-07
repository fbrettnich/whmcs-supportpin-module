<!-- 
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
 -->
 <link rel="stylesheet" href="../modules/addons/supportpin/templates/assets/css/admin.css">

 
<h3>{$addonlang['admin_title_activepins']}</h3>
<table id="supportpinCustomers" style="width:100%">
    <tr>
        <th style="width: 5%">{$addonlang['admin_table_id']}</th>
        <th style="width: 20%">{$addonlang['admin_table_firstname']}</th>
        <th style="width: 20%">{$addonlang['admin_table_lastname']}</th>
        <th style="width: 10%">{$addonlang['admin_table_pin']}</th>
        <th style="width: 25%">{$addonlang['admin_table_updated']}</th>
        <th style="width: 10%"></th>
    </tr>
    
    {foreach from=$tplVars.clients item=$client}
    <tr>
        <td>{$client->customerid}</td>
        <td>{$client->firstname}</td>
        <td>{$client->lastname}</td>
        <td>{$client->pin}</td>
        <td>{$client->updated_at}</td>
        <td style="text-align: center;"><a href="clientssummary.php?userid={$client->customerid}" class="btn btn-success">{$addonlang['admin_table_gocustomer']}</a></td>
    </tr>
    {/foreach}