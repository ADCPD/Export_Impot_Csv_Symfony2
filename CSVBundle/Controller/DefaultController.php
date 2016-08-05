<?php

namespace Administration\CSVBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CSVBundle:Default:index.html.twig', array('name' => $name));
    }
}
