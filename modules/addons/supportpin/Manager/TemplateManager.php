<?php
/*
 * ###############################################################################
 * File: TemplateManager.php
 * Project: Manager
 * File Created: Thursday, 12th August 2021 12:27:31 pm
 * Author: Thomas Brinkmann (doyl@dsh.icu)
 * -----
 * Last Modified: Thursday, 12th August 2021 12:54:59 pm
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