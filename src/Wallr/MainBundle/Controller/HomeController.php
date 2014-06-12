<?php

namespace Wallr\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('WallrMainBundle:Home:home.html.twig', array(
            
        ));
    }
}
