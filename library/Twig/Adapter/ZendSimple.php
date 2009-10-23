<?php

/**
 * Adapter class to use Twig inside Zend Framework for view-parsing
 * This class is meant to be used on a controller basis, without features 
 * like helper or layout support
 *  
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 * @category Twig
 * @package Twig_Adapter
 */

require_once "Twig/Adapter/Zend/Abstract.php";

class Twig_Adapter_ZendSimple extends Twig_Adapter_Zend_Abstract
{}
