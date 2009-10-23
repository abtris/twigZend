<?php

/**
 * token parser for the zfview tag, which routes calls
 * to a Zend_View helper object
 * 
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 * @category Twig
 * @package Twig_Adapter
 * @subpackage Twig_Adapter_Zend_TokenParser
 */
class Twig_Adapter_Zend_TokenParser_ViewHelper extends Twig_TokenParser
{

    /**
     * parse node
     * 
     * @param Twig_Token $token
     * @return Twig_Adapter_Zend_LayoutContent_Node
     */
    public function parse(Twig_Token $token)
    {
        $s = $this->parser->getStream();
        $callChain = array();
        
        // output is enabled by default
        $output = true;
        
        do {
            // method-name
            $name = $s->expect(Twig_Token::NAME_TYPE)->getValue();
            $type = 'var';
            $args = array();
            
            // parse arguments
            if ($s->test(Twig_Token::OPERATOR_TYPE, '(')) {
               $type = 'method';
               $args = $this->_getArgumentChain($s, '()', false);
            }
            
            // push on callchain
            $callChain[] = array('name' => $name, 'type' => $type,
            					 'args' => $args);
            
        } while (
            $s->test(Twig_Token::OPERATOR_TYPE, '.') && 
            $s->expect(Twig_Token::OPERATOR_TYPE, '.')
        );
        
        
        // detect "silent" option
        if ($s->test(Twig_Token::NAME_TYPE)) {
            $s->expect(Twig_Token::NAME_TYPE);
            $output = false;
        }
        
        // close tag block
        $s->expect(Twig_Token::BLOCK_END_TYPE);
        
        return new Twig_Adapter_Zend_Node_ViewHelper($callChain, $output);
    }
    
    /**
     * parse an argument chain
     * 
     * @param Twig_TokenStream $s
     * @param string $delimiter 
     *        any two operators, e.g. (), [], {}
     * @param bool $associative 
     *        forces matching of "key: value" pairs instead of just arg1, arg2,..
     * @return array
     */
    protected function _getArgumentChain(Twig_TokenStream $s, $delimiter = '()', $associative=false)
    {
        $arguments = array();
        $finished = true;
        
        // argument chain starts with 
        $s->expect(Twig_Token::OPERATOR_TYPE, $delimiter[0]);

        // break on closing )
        while (!$s->test(Twig_Token::OPERATOR_TYPE, $delimiter[1])) {
        
            
            $key = count($arguments);
            
            // associative forces "key: value" synthax
            if ($associative) {
            	$key = $this->_getPrimitiveArgument($s);
            	$s->expect(Twig_Token::OPERATOR_TYPE, ':');
            }
            
			$arguments[$key] = $this->_getArgument($s);
			$finished = true;

            // check for , as an argument delimiter
            if (
                $s->test(Twig_Token::OPERATOR_TYPE, ',') &&
                $s->expect(Twig_Token::OPERATOR_TYPE, ',')
            ) {
                $finished = false;
            }
        }
        
        if (!$finished) {
            throw new Twig_SyntaxError('Invalid argument listing, another argument is expected');
        }

        // close argument chain
        $s->expect(Twig_Token::OPERATOR_TYPE, $delimiter[1]);
        return $arguments;
    }
    
    /**
     * get an argument from the token stream
     * 
     * @param Twig_TokenStream $s
     * @return mixed
     */
    protected function _getArgument(Twig_TokenStream $s) 
    {
		// array synthax: ["ab c", de, 12, 34.56]
        if ($s->test(Twig_Token::OPERATOR_TYPE, '[')) {
			return $this->_getArgumentChain($s, '[]', false);
        }
        
        // object/assoc array synthax
        // {"abc": 12, a: b}
        if ($s->test(Twig_Token::OPERATOR_TYPE, '{')) {
			return $this->_getArgumentChain($s, '{}', true);
        }
        
        // primitive
        return $this->_getPrimitiveArgument($s);
    }
    
    /**
     * get a primitive (string, quoted string, integer or float) 
     * argument
     *
     * @param Twig_TokenStream $s
     * @return mixed
     */
    protected function _getPrimitiveArgument(Twig_TokenStream $s)
    {
		// integer & float
		if ($s->test(Twig_Token::NUMBER_TYPE)) {
            return $s->expect(Twig_Token::NUMBER_TYPE)->getValue();
        }
        
    	// boolean
        if ($s->test(Twig_Token::NAME_TYPE, array('true', 'false'))) {
            return ($s->expect(Twig_Token::NAME_TYPE)->getValue() == 'true');
        }
        
        // string
        if ($s->test(Twig_Token::NAME_TYPE)) {
            return $s->expect(Twig_Token::NAME_TYPE)->getValue();
        }

		// quoted string
        return $s->expect(Twig_Token::STRING_TYPE)->getValue();
    }
    
    /**
     * get tag
     * 
     * @return string  
     */
    public function getTag()
    {
        return 'zf';
    }
}