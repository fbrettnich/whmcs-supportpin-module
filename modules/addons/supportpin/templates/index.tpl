<!--
* WHMCS-SupportPIN - Let your customers generate a support/phone pin to identify your customers faster, for example on the phone
*
* Copyright (c) 2021 Felix Brettnich
*
* This file is part of fbrettnich/whmcs-supportpin-module
*
* Licensed under GPL-3.0 (https://github.com/fbrettnich/whmcs-supportpin-module/blob/main/LICENSE)
-->
<style>
    .icon-rotate:hover {
        animation: fas-spin 2s infinite linear;
    }
    @-webkit-keyframes fas-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}
    @keyframes fas-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}
</style>

<div style="text-align: center;">
    <div style="font-size: 35px; margin: 50px;">{$lang_client_title}</div>

    <div style="font-size: 55px; color: greenyellow; margin: 50px;">{$supportpin}</div>

    <p>{$lang_client_info}</p>

    <a href="" class="btn btn-success" style="width: 50%; margin: 50px;"><i class="fas fa-sync-alt icon-rotate"></i> {$lang_client_regenerate}</a>
</div>