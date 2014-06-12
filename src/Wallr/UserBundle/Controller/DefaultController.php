<?php

namespace Wallr\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WallrUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
