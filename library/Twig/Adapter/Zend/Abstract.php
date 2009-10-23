<?php

/**
 * abstract adapter base class to use Twig inside Zend Framework (views)
 *  
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 * @category Twig
 * @package Twig_Adapter
 * @subpackage Twig_Adapter_Zend
 */
abstract class Twig_Adapter_Zend_Abstract implements Zend_View_Interface
{

    /**
     * assigned vars
     * @var array
     */
    protected $_assigned = array();

    /**
     * twig environment
     * @var Twig_Environment
     */
    protected $_twig;
    
    /**
     * class constructor
     * 
     * @param Twig_Environment $twig
     * @param array $options
     */
    public function __construct(Twig_Environment $twig=null, $options=array())
    {
        $this->_twig = $twig ?  $twig : new Twig_Environment();

        // TODO: parse options
    }
    
    /**
     * Get the twig environment
     * 
     * @return Twig_Environment
     */
    public function getEngine()
    {
        return $this->_twig;
    }

    /**
     * Set the path to the templates
     *
     * @param string $path The directory to set as the path.
     * @return void
     */
    public function setScriptPath($path)
    {
        $loader = $this->_twig->getLoader();
        if ($loader instanceof Twig_Loader_Filesystem) {
            $loader->setPaths($path);
        }
    }

    
    /**
     * Retrieve the current template directory
     *
     * @return array|string
     */
    public function getScriptPaths()
    {
        $loader = $this->_twig->getLoader();
        return ($loader instanceof Twig_Loader_FileSystem) 
            ? $loader->getPaths()
            : '';
    }

    /**
     * No basepath support on twig, therefore alias for "setScriptPath()"
     *
     * @see setScriptPath()
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function setBasePath($path, $prefix = 'Zend_View')
    {
        $this->setScriptPath($path . '/scripts');
    }

    /**
     * No basepath support on twig, therefore alias for "setScriptPath()"
     *
     * @see setScriptPath()
     * @param string $path
     * @param string $prefix Unused
     * @return void
     */
    public function addBasePath($path, $prefix = 'Zend_View')
    {
        $this->setScriptPath($path . '/scripts');
    }

    /**
     * Assign a variable to the template
     *
     * @param string $key The variable name.
     * @param mixed $val The variable value.
     * @return void
     */
    public function __set($key, $val)
    {
        $this->assign($key, $val);
    }

    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->_assigned[$key]);
    }

    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->_assigned[$key]);
    }

    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing
     * an array of key => value pairs to set en masse.
     *
     * @see __set()
     * @param string|array $spec The assignment strategy to use (key or
     * array of key => value pairs)
     * @param mixed $value (Optional) If assigning a named variable,
     * use this as the value.
     * @return void
     */
    public function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $this->_assigned = array_merge($this->_assigned, $spec);
        }
        
        $this->_assigned[$spec] = $value;
    }

    /**
     * Clear all assigned variables
     *
     * Clears all variables assigned to Zend_View either via
     * {@link assign()} or property overloading
     * ({@link __get()}/{@link __set()}).
     *
     * @return void
     */
    public function clearVars()
    {
        $this->_assigned = array();
    }

    /**
     * Processes a template and returns the output.
     *
     * @param string $name The template to process.
     * @return string The output.
     */
    public function render($name)
    {
        $template = $this->_twig->loadTemplate($name);
        return $template->render($this->_assigned);
    }
}


