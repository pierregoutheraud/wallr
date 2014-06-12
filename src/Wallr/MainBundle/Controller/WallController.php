<?php

namespace Wallr\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wallr\APIBundle\Entity\Feed;

class WallController extends Controller
{
    public function indexAction()
    {
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        if(!$user)
            return $this->redirect($this->generateUrl('home'));
        
        $em = $this->getDoctrine()->getManager();
        
        // Get le feed avec l'id et l'user en cours
        $feeds = $em->getRepository('WallrAPIBundle:Feed')->findBy(array(
            "user" => $user
        ));
        
        return $this->render('WallrMainBundle:Wall:wall.html.twig', array(
            'feeds' => $feeds
        ));
    }
    
    public function feedAction()
    {
        return $this->redirect($this->generateUrl('wall'));
    }
    
}
