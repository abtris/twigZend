<?php

/**
 * Fullfeatured adapter class to use Twig inside Zend Framework (views)
 * requires setup in config.ini
 *
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 * @category Twig
 * @package Twig_Adapter
 */
class Twig_Adapter_Zend extends Twig_Adapter_Zend_Abstract
{
    
    /**
     * layout view, and view helper-transporter
     * @var Zend_View
     */
    protected $_zendView;
    
    /**
     * @override
     */
    public function __construct($twig=null, $options=array())
    {
        parent::__construct($twig, $options);
        
        $this->_twig->addExtension(
            new Twig_Adapter_Zend_Extension($this)
        );
        
        // we only support Twig_Loader_Filesystem
        if (!$this->_twig->getLoader() instanceof Twig_Loader_Filesystem) {
            $this->_twig->setLoader(new Twig_Loader_Filesystem(''));
        }
        
        $this->_initLayout();
    }
    
    public function __set($key, $value) 
    {
    	parent::__set($key, $value);
    	$this->_zendView->{$key} = $value;
    }
    
    /**
     * tie adapter to layout
     * 
     * @return void
     */
    protected function _initLayout()
    {
    	$layout = Zend_Layout::getMvcInstance();
        if ($layout) {
            $this->_zendView = Zend_Layout::getMvcInstance()->getView();
        }
    	
    	if ($this->_zendView == null) {
            $this->_zendView = new Zend_View();
        } 
    }
    
    /**
     * @override
     */
    public function setScriptPath($path)
    {
        $this->_zendView->setScriptPath($path);
        return parent::setScriptPath($path);
    }
    
    /**
     * @override
     */
    public function setBasePath($path, $prefix = 'Zend_View')
    {
        $this->_zendView->setBasePath($path, $prefix);
        return parent::setBasePath($path);
    }
    
    /**
     * @override
     */
    public function addBasePath($path, $prefix = 'Zend_View')
    {
        $this->_zendView->addBasePath($path, $prefix);
        return parent::addBasePath($path);
    }
    
    /**
     * retrieve Zend_view object
     * 
     * @return Zend_View
     */
    public function getZendView()
    {
        return $this->_zendView;
    }
    
    /**
     * unregister as default renderer, and reset to Zend_View
     * 
     * @return void
     */
    public function unregister()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($this->_zendView);     
    }
    
    /**
     * register as default renderer
     * 
     * @return unknown_type
     */
    public function register()
    {
    	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($this);  
    }

}


