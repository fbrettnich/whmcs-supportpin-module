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
namespace Fbrettnich\WhmcsSupportpinModule\Manager;

class TemplateManager {



    protected $basePath;
    protected $currentTemplate;
    protected $currentTPLFile;
    
    
    public function __construct(string $basepath, $currentTemplate = "admin/index.tpl"){
        $this->setBasePath($basepath . '/templates/');
        $this->setCurrentTemplate($this->getBasePath() . $currentTemplate);
        $this->setCurrentTPLFile($currentTemplate);
    
    }
    
        
    public function getTemplate($page, $isAdmin = false){
        return ($isAdmin ? $this->getBasePath() ."admin/" . $page .".tpl"  : "templates/client/" . $page . ".tpl");
    }
    
    
    /**
     * Get the value of currentTemplate
     */ 
    public function getCurrentTemplate()
    {
        return $this->currentTemplate;
    }
    
    /**
     * Set the value of currentTemplate
     *
     * @return  self
     */ 
    public function setCurrentTemplate($currentTemplate)
    {
        $this->currentTemplate = $currentTemplate;
    
        return $this;
    }
    
    /**
     * Get the value of basePath
     */ 
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    /**
     * Set the value of basePath
     *
     * @return  self
     */ 
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    
        return $this;
    }
    
    /**
     * Get the value of currentTPLFile
     */ 
    public function getCurrentTPLFile()
    {
    return $this->currentTPLFile;
    }
    
    /**
     * Set the value of currentTPLFile
     *
     * @return  self
     */ 
    public function setCurrentTPLFile($currentTPLFile)
    {
    $this->currentTPLFile = $currentTPLFile;
    
    return $this;
    }



}