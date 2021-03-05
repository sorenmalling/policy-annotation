<?php

namespace Meteko\PolicyAnnotation\Controller;

use Neos\Flow\Mvc\Controller\ActionController;
use Meteko\PolicyAnnotation\Annotations\Policy;

class TestController extends ActionController
{
    /**
     * @Policy(role="Meteko.PolicyAnnotation:TestRole", permission="grant")
     */
    public function indexAction()
    {

    }
}