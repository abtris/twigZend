<?php

/**
 * this class parses the callstack of the zfview-tag (for token-parsing 
 * see Twig_Adapter_Zend_TokenParser_ViewHelper)
 * 
 * 
 * @see Twig_Adapter_Zend_TokenParser_ViewHelper
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 * @category Twig
 * @package Twig_Adapter
 * @subpackage Twig_Adapter_Zend_Node
 */
class Twig_Adapter_Zend_Node_ViewHelper extends Twig_Node
{
    
    /**
     * callchain array
     * 
     * @var array
     */
    protected $_callChain;
    
    /**
     * output is enabled
     * 
     * @var boolean
     */
    protected $_outputEnabled;
    
    /**
     * class constructor
     * 
     * @param Twig_Adapter_Zend $adapter
     */
    public function __construct(array $callChain, $outputEnabled)
    {
        $this->_callChain = $callChain;
        $this->_outputEnabled = $outputEnabled;
    }
    
    
    /**
     * compile node
     * 
     * @param Twig_Compiler $compiler
     * @return void
     */
    public function compile($compiler)
    {
    	$strCallChain = "";
        foreach ($this->_callChain as $call) {

            $strCallChain .= "->{$call['name']}";
        	if ($call['type'] == 'method') {
                $strCallChain .= $this->_prepareArguments($call['args']);
            }
        }
        
		$echo = $this->_outputEnabled ? 'echo ' : '';
        
        $compiler->addDebugInfo($this)
                 ->write($echo . '$this->getEnvironment()->getExtension(\'' 
                       . Twig_Adapter_Zend_Extension::NAME . '\')->getAdapter()->getZendView()')
                 ->raw("\n")
                 ->write($strCallChain)
                 ->raw(";\n");
    }
    
    /**
     * prepare functiona rguments
     * 
     * @param array $args
     * @return string
     */
    protected function _prepareArguments(array $args)
    {
    	$args = array_map(array($this, "prepare"), $args);
    	return "(" . implode(", ", $args) . ")";
    }
    
    /**
     * prepare an array of values
     * 
     * @param array $args
     * @return string
     */
    protected function _prepareArray(array $args)
    {
    	$preparedArgs = array();
    	foreach ($args as $k=>$v) {
    		$preparedArgs[] = $this->prepare($k) 
    		                . " => "
    		                . $this->prepare($v);
    	}
    	
    	return "array(" . implode(", ", $preparedArgs) . ")";
    }
    
    /**
     * prepare a value according to its type
     * 
     * @param mixed $var
     * @return string
     */
    public function prepare($var)
    {
   
    	// no switch on gettype() because of
    	// warning on: <http://php.net/gettype>
    	if (is_numeric($var)) {
    		return $var;
    	}
    	
    	if (is_string($var)) {
    		return '\'' . addslashes($var) . '\'';
    	}
    	
    	if (is_bool($var)) {
    		return $var ? 'true' : 'false';
    	}
    	
    	if (is_array($var)) {
    		return $this->_prepareArray($var);
    	}
    	
    	throw new Twig_SyntaxError('Cannot handle type of $var: ' . gettype($var));
    }
    
}