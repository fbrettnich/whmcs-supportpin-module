<!--
* WHMCS-SupportPIN - Let your customers generate a support/phone pin to identify your customers faster, for example on the phone
*
* Copyright (c) 2021 Felix Brettnich
*
* This file is part of fbrettnich/whmcs-supportpin-module
*
* Licensed under GPL-3.0 (https://github.com/fbrettnich/whmcs-supportpin-module/blob/main/LICENSE)
-->

<div style="text-align: center;">
    <div style="font-size: 35px; margin: 50px;">{$lang_client_title}</div>

    <div id="sPIN" style="font-size: 55px; color: greenyellow; margin: 50px;">{$tplVars.supportpin}</div>

    <p>{$lang_client_info}</p>

    <a href="#" onclick="RenewPIN()" class="btn btn-success" style="width: 50%; margin: 50px;"><i class="fas fa-sync-alt"></i> {$lang_client_regenerate}</a>
</div>



<script>
    function Request(url, callback){
        $.ajax({
           type: "POST",
           crossDomain: false,
           url: url,
           data: { "PIN": true },
           success: function(respond){
                callback(respond);
           },
           error: function(response){
               console.log(response.status);
           }
       });
    };
    
    function RenewPIN(){
        Request("index.php?m=supportpin&page=renew", function(response){
            $("#sPIN").html(response.PIN);
        })
    }
    </script>