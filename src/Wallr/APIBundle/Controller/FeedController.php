<?php

namespace Wallr\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Wallr\APIBundle\Entity\Feed;
use Wallr\APIBundle\Entity\Image;
use Wallr\UserBundle\Entity\User;
use \SimplePie;
use \simple_html_dom;

use DateTime;

class FeedController extends Controller
{
    
    public function restFeedAction( $id )
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // POUR DEBUG
        $request = $this->getRequest();
        if( $request->query->has("method") )
            $method = $request->query->get("method");
        
        switch ($method) {
            case 'PUT':
                // UPDATE
                $response = $this->update( $id );
                break;
            
            case 'POST':
                // CREATE
                $response = $this->addFeed();
                break;
            
            case 'GET':
                break;
                
            case 'HEAD':
                break;
            
            case 'DELETE':
                $response = $this->removeFeed($id);
                break;
            
            case 'OPTIONS':
                break;
            
            default:
                break;
        }
        
        return $response;
    }
    
    public function update( $id )
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
        
        $this->refresh($feed);
        
        $jsonFeed = array();
        $countUnreadImages = 0;
        $countImages = 0;
        foreach( $feed->getImages() as $image )
        {
            $countImages++;
            if( !$image->getIsRead() )
                $countUnreadImages++;
        }

        $jsonFeed['id'] = $feed->getId();
        $jsonFeed['countImages'] = $countImages;
        $jsonFeed['countUnreadImages'] = $countUnreadImages;
        
        return new Response(json_encode($jsonFeed));
    }
    
    public function addFeed()
    {

        
//        $request = $this->getRequest();
//        if( $request->query->has("url") )
//            $url = $request->query->get("url");
        
        // Get json data sent from backbone // http://stackoverflow.com/questions/9597052/how-to-retrieve-request-payload
        $feedData = json_decode(file_get_contents('php://input'), true);
        $url = $feedData['url'];
        
        if( empty($url))
            return new Response(json_encode('no_url'));
        
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        if( !$user  || $user == 'anon.' )
            return new Response(json_encode('not_logged'));
        
        // Get le feed avec l'id et l'user en cours
        $feedBDD = $em->getRepository('WallrAPIBundle:Feed')->findOneBy(array(
            "url" => $url,
            "user" => $user
        ));
        
        if( $feedBDD )
            return new Response(json_encode('already_added'));
        
        $feed = new Feed;
        $feed->setUrl( $url );
        $feed->setUser( $user );
        
        if( strpos($url,'dribbble.com') !== false ) 
        {
            $feed->setSource('dribbble');
        }
        else
            $feed->setSource('rss');
        
        $em->persist( $feed );
        $em->flush();
        
        $this->refresh($feed);
        
//        $images = $feed->getImages();
        // /!\ Obligé de récup les images de la BDD (récup depuis refresh?)
        $images = $em->getRepository('WallrAPIBundle:Image')->findBy(array(
            "feed" => $feed
        ));
        
        if( count($images) == 0 )
        {
            $em->remove( $feed );
            $em->flush();
            return new Response(json_encode( 'no_photo' ));
        }
        
        $firstImage = $images[0];
        
        $countUnreadImages = 0;
        $countImages = count($images);
        foreach( $images as $image )
        {
            if( !$image->getIsRead() )
                $countUnreadImages++;
        }
        
        $arrayFeed = array(
            "id" => $feed->getId(),
            "pp" => $firstImage->getUrl(),
            "name" => $feed->getName(),
            "url" => $feed->getUrl(),
            "countImages" => $countImages,
            "countUnreadImages" => $countUnreadImages
        );
        
        return new Response(json_encode( $arrayFeed ));
    }
    
    public function getFeedsListAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if( !$user )
            return new Response(json_encode('notlogged'));
        
        $em = $this->getDoctrine()->getManager();
        $feedsDB = $em->getRepository('WallrAPIBundle:Feed')->findBy(array(
            "user" => $user
        ));
        
//        $this->refresh( $feedsDB );
        
        $jsonFeeds = array();
        $totalCountImages = 0;
        $totalCountUnreadImages = 0;
        foreach( $feedsDB as $feedDB )
        {
            $jsonFeed = array();
            
            $sfImages = $feedDB->getImages();
            $sfFirstImage = $sfImages[0];
            
            $jsonFeed['id'] = $feedDB->getId();
            
            // PP
            if( $sfFirstImage )
            {
                $pp = $sfFirstImage->getUrl();
                $jsonFeed['pp'] = $pp;
            }
            else
            {
                $jsonFeed['pp'] = null;
            }
            
            // URL
            $jsonFeed['url'] = $feedDB->getUrl();
            
            // NAME
            $feedName = $feedDB->getName();
            if( !empty($feedName) )
                $jsonFeed['name'] = $feedName;
            else
                $jsonFeed['name'] = $jsonFeed['url'];
            
            $countUnreadImages = 0;
            $countImages = 0;
            foreach( $sfImages as $sfImage )
            {
                $countImages++;
                if( !$sfImage->getIsRead() )
                    $countUnreadImages++;
            }
            
            $jsonFeed['countImages'] = $countImages;
            $jsonFeed['countUnreadImages'] = $countUnreadImages;
            
            $jsonFeeds[] = $jsonFeed;
            
            $totalCountImages += $countImages;
            $totalCountUnreadImages += $countUnreadImages;
        }
        
        // ALL FEEDS
        // ON l'ajoute en haut de l'array
        if( count($jsonFeeds) > 0 )
        {
            array_unshift($jsonFeeds, array(
                "id" => 0,
                "pp" => null,
                "name" => "All feeds",
                "url" => "",
                "countImages" => $totalCountImages,
                "countUnreadImages" => $totalCountUnreadImages
            ));
        }
        
        return new Response(json_encode($jsonFeeds));
    }
    
    public function removeFeed( $id )
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        // Get le feed avec l'id et l'user en cours
        $em = $this->getDoctrine()->getManager();
        $feed = $em->getRepository('WallrAPIBundle:Feed')->findOneBy(array(
            "id" => $id,
            "user" => $user
        ));
        
        if( !$feed )
            return new Response(json_encode('wrong_user_or_id'));
        
        $em->remove($feed);
        $em->flush();
        
        return new Response(json_encode('removed'));
    }
    
    // METTRE DANS LE REPO OU SERVICE
    public function refresh( $feeds )
    {
        
        // Si il n'y a qu'un feed on le met dans un array pour le foreach
        if( !is_array($feeds) )
            $feeds = array($feeds);
        
        $em = $this->getDoctrine()->getManager();
        
        $imagesURLS = array();
        foreach($feeds as $feed)
        {
            
            // DRIBBBLE
            if( $feed->getSource() == "dribbble" )
            {
                $this->refreshDribbble($feed);
                continue;
            }
            
            // RSS
            $url = $feed->getUrl();

            $simplePieFeed = new SimplePie();
            $simplePieFeed->enable_cache(true);
            $simplePieFeed->set_cache_location('../app/cache/simplepie');
    //        $simplePieFeed  ->set_cache_duration( 3600 );
            $simplePieFeed->set_feed_url( $url );
            $simplePieFeed->init();
            $simplePieFeed->handle_content_type();
            
            $feed->setName( $simplePieFeed->get_title() );
            
            foreach( $simplePieFeed->get_items() as $item )
            {
                $htmlDOM = new simple_html_dom;
                $htmlDOM->load($item->get_content());
                $imageTAG = $htmlDOM->find('img', 0); 
                
                if( $imageTAG )
                    $imageURL = $imageTAG->src;
                else
                    continue;

                // Si l'image n'est pas déjà dans le feed en question
                $image = $em->getRepository('WallrAPIBundle:Image')->findOneBy(
                        array(
                            "url" => $imageURL,
                            "feed" => $feed
                        )
                );

                if( !$image )
                {
                    $image = new Image;
                    $image->setUrl($imageURL);
                    $image->setFeed($feed);
                    $image->setLink( $item->get_link() );
                    
//                    var_dump( $item->get_link() );
                    
                    $dateItem = $item->get_date("Y-m-d H:i:s");
                    
                    if( !empty($dateItem) )
                        $imageDate = new DateTime( $dateItem );
                    else
                        $imageDate = new DateTime();
                    
                    $image->setDate( $imageDate );
                    
                    $em->persist($image);
                    $em->flush(); // Obligé de flush là pour récup l'id
                }
                
                $imagesURLS[] = array(
                    'id' => $image->getId(),
                    'url' => $image->getUrl(),
                    'link' => $image->getLink()
                );
                
            }
            
            $em->flush();
        }
        
//        return $feeds;
        
    }
    
    public function refreshDribbble( $feed )
    {
        $countShots = 100;
        
        // jSON URL which should be requested
        $list = 'popular'; // debuts, everyone, popular
        $page = 1;
        $per_page = 50;
        $countPages = $countShots / $per_page;
        
        $dribbbleAPI = new \Dribbble\Api\Client();
        
        $allShots = array();
        for($page;$page<=$countPages;$page++)
        {
            $shots = $dribbbleAPI->getShotsList($list, $page, $per_page);
            
            foreach( $shots['shots'] as $shot )
                $allShots[] = $shot;
        }
        
        $feed->setName( 'Dribbble / ' . ucfirst($list) );
        
        $em = $this->getDoctrine()->getManager();
        foreach( $allShots as $shot )
        {
            // Si l'image n'est pas déjà dans le feed en question
            $image = $em->getRepository('WallrAPIBundle:Image')->findOneBy(
                    array(
                        "url" => $shot['image_url'],
                        "feed" => $feed
                    )
            );

            if( !$image )
            {
                $image = new Image;
                $image->setUrl( $shot['image_url'] );
                $image->setFeed( $feed );
                $image->setLink( $shot['url'] );

                $dateItem = $shot['created_at'];
                $imageDate = new DateTime( $dateItem );
                $image->setDate( $imageDate );

                $em->persist($image);
                $em->flush(); // Obligé de flush là pour récup l'id
            }

            $imagesURLS[] = array(
                'id' => $image->getId(),
                'url' => $image->getUrl(),
                'link' => $image->getLink()
            );
        }
        
        return $imagesURLS;
    }

}
