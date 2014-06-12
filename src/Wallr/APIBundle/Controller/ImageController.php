<?php

namespace Wallr\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Wallr\APIBundle\Entity\Feed;
use Wallr\APIBundle\Entity\Image;
use Wallr\UserBundle\Entity\User;

class ImageController extends Controller
{
    
    public function readImageAction( $idImage )
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('WallrAPIBundle:Image')->findOneBy(array(
            "id" => $idImage
        ));
        $image->setIsRead(true);
        $em->flush();
        return new Response(json_encode('success'));
    }


}
