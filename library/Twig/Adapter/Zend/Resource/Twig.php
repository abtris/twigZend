<?php

/**
 * Twig Resource Plugin for Zend_Application
 * 
 * @category Twig
 * @package Twig_Adapter
 * @subpackage Twig_Adapter_Zend_Resource
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 */
class Twig_Adapter_Zend_Resource_Twig extends 
    Zend_Application_Resource_ResourceAbstract
{

    /**
     * initialize twig-view
     * 
     * @return void
     */
    public function init()
    {
        // bootstrap layout first
        try {
        	$this->getBootstrap()->bootstrap('layout');
        } catch (Zend_Application_Bootstrap_Exception $e) {
        	// we do not *require* a layout
        }
        
        // set twig as "main_view"
        $twigView = new Twig_Adapter_Zend(null, $this->getOptions());
        $twigView->register();    
    }

}