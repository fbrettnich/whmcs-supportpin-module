<!-- 
 ###############################################################################
 File: index.tpl
 Project: admin
 File Created: Thursday, 12th August 2021 12:22:10 pm
 Author: Thomas Brinkmann (doyl@dsh.icu)
 -----
 Last Modified: Thursday, 12th August 2021 12:52:18 pm
 Modified By: Thomas Brinkmann (doyl@dsh.icu>)
 -----
 Copyright 2021 - Thomas Brinkmann. All Rights Reserved.
 -----
 License Text 
 Es ist Ihnen untersagt diese Software zu kopieren, manipulieren, verbreiten oder anderweitig ohne ausdrückliche Erlaubnis zu nutzen.
 Sie dürfen ebenfalls nicht den Copyright Hinweis entfernen. 
 
 It is prohibited to copy, manipulate, distribute or otherwise use this software without express permission.
 You may also not remove the copyright notice. 
 -----
 ###############################################################################
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