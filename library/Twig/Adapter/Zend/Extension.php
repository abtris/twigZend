<?php

/**
 * Twig extension for Zend Framework adapter
 * 
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 * @category Twig
 * @package  Twig_Adapter
 * @subpackage Twig_Adapter_Zend
 */
class Twig_Adapter_Zend_Extension extends Twig_Extension
{
    
	const NAME = 'Zend';
	
    /**
     * @var Twig_Adapter_Zend
     */
    protected $_adapter;
    
    /**
     * class constructor
     * 
     * @param Twig_Adapter_Zend $adapter
     */
    public function __construct(Twig_Adapter_Zend $adapter)
    {
        $this->_adapter = $adapter;
    }
    
    /**
     * @override
     */
    public function getTokenParsers()
    {
        return array(new Twig_Adapter_Zend_TokenParser_ViewHelper());
    }
    
    /**
     * @override
     */
    public function getName()
    {
        return self::NAME;
    }
    
    /**
     * retrieve adapter instance
     * 
     * @return Twig_Adapter_Zend
     */
    public function getAdapter()
    {
    	return $this->_adapter;
    }

}