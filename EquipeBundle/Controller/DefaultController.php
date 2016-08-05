<?php

namespace CMS\EquipeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction( )
    {
        return $this->render('EquipeBundle:Default:index.html.twig' );
    }
}
