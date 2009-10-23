<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $this->view->var = "Test's & Test";

        $navigation = array(
                            array("href" => "http://framework.zend.com", "caption" => "Zend Framework"),
                            array("href" => "http://www.zend.com", "caption" => "Zend")
                            );


        $this->view->navigation = $navigation;
    }


}

