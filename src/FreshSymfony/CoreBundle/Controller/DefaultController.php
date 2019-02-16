<?php

namespace FreshSymfony\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FreshSymfonyCoreBundle:Default:index.html.twig');
    }
}
