<?php


class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

        protected function _initAutoload ()
        {
            $moduleLoader = new Zend_Application_Module_Autoloader(
            array('namespace' => '' , 'basePath' => APPLICATION_PATH));
            $moduleLoader->addResourceType('formax', '../library/Twig', 'Twig');
            return $moduleLoader;
        }

	public function _initView()
	{	
       	        $view = new Twig_Adapter_Zend();
	        $view->setScriptPath(APPLICATION_PATH . '/views/scripts/');

                $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
                $viewRenderer->setView($view); 
	}

}

