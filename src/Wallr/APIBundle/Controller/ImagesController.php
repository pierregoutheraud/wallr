<?php

namespace Wallr\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Wallr\APIBundle\Entity\Feed;
use Wallr\APIBundle\Entity\Image;
use Wallr\UserBundle\Entity\User;

class ImagesController extends Controller
{
    
    private $countImages = 30;
    
    public function restFeedImagesAction( $id )
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // POUR DEBUG
        $request = $this->getRequest();
        if( $request->query->has("method") )
            $method = $request->query->get("method");
        
        switch ($method) {
            case 'PUT':
                // UPDATE
                break;
            
            case 'POST':
                // CREATE
                break;
            
            case 'GET':
                if ($id == 0)
                    $response = $this->getFeedsImages();
                else
                    $response = $this->getFeedImages($id);
                break;
                
            case 'HEAD':
                break;
            
            case 'DELETE':
                break;
            
            case 'OPTIONS':
                break;
            
            default:
                break;
        }
        
        return $response;
    }
    
        
    // D'abord req pr récup existant et ensuite refresh
    public function getFeedsImages()
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        // Get les feeds avec l'id et l'user en cours
        $feeds = $em->getRepository('WallrAPIBundle:Feed')->findBy(array(
            "user" => $user
        ));
        
        if( !$feeds )
            return new Response(json_encode('wrong_user_or_id'));
        
        // On récup les nouvelles photos si c'est la page 1
//        if( $page == 1 )
//            $this->refresh($feeds);
        
        $page = 1;
        $images = $em->getRepository('WallrAPIBundle:Image')->getAllUnreadImages( $user, $page, $this->countImages );
        
        // On get celle de la BDD (donc OLD + NEW)
        $imagesURLS = array();
        foreach( $images as $image )
        {
            $imagesURLS[] = array(
                'id' => $image->getId(),
                'url' => $image->getUrl(),
                'link' => $image->getLink(),
                'feed' => array(
                    'id' => $image->getFeed()->getId()
                )
            );
        }
        
        return new Response(json_encode($imagesURLS));
    }
    
    public function getFeedImages( $id )
    {
        
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        // Get le feed avec l'id et l'user en cours
        $feed = $em->getRepository('WallrAPIBundle:Feed')->findOneBy(array(
            "id" => $id,
            "user" => $user
        ));
        
        if( !$feed )
            return new Response(json_encode('wrong_user_or_id'));
        
        // On récup les nouvelles photos si c'est la page 1
//        if( $page == 1 )
//            $this->refresh($feed);
        
        $page = 1;
        $images = $em->getRepository('WallrAPIBundle:Image')->getUnreadImages( $feed, $page, $this->countImages );
        
        // On get celle de la BDD (donc OLD + NEW)
        $imagesURLS = array();
        foreach( $images as $image )
        {
            $imagesURLS[] = array(
                'id' => $image->getId(),
                'url' => $image->getUrl(),
                'link' => $image->getLink(),
                'feed' => array(
                    'id' => $image->getFeed()->getId()
                )
            );
        }
        
        return new Response(json_encode($imagesURLS));
    }
    
}
