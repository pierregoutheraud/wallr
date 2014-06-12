<?php

namespace Wallr\APIBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends EntityRepository
{
    
    public function getAllUnreadImages( $user, $page, $countImages )
    {
        
        $limit = $countImages;
        $offset = ($page-1) * $countImages;
        
        $dql = "SELECT i
            FROM WallrAPIBundle:Image i
            INNER JOIN i.feed f
            INNER JOIN f.user u
            WHERE i.isread = '0'
            AND u.id = :userID
            ORDER BY i.date DESC
            ";

        $parameters = array(
            "userID" => $user->getId()
        );
        
//        $dql = "SELECT i
//            FROM WallrAPIBundle:Image i
//            WHERE i.id IN (:ids)
//            ";
//            
//        $array = array(1, 12, 26, 7);
//        shuffle($array);
//        var_dump($array);
//        
//        $parameters = array(
//            "ids" => $array
//        );
        
        $query = $this->_em->createQuery($dql)
        ->setParameters($parameters)
        ->setFirstResult($offset)
        ->setMaxResults($limit);
        
        $images = $query->getResult();

        return $images;
        
    }
    
    public function getUnreadImages( $feed, $page, $countImages )
    {
        
        $limit = $countImages;
        $offset = ($page-1) * $countImages;
        
        $dql = "SELECT i
            FROM WallrAPIBundle:Image i
            INNER JOIN i.feed f
            WHERE i.isread = '0'
            AND f.id = :feedID
            ORDER BY i.date DESC
            ";

        $parameters = array(
            "feedID" => $feed->getId()
        );
        
        $query = $this->_em->createQuery($dql)
        ->setParameters($parameters)
        ->setFirstResult($offset)
        ->setMaxResults($limit);
        
        $images = $query->getResult();

        return $images;
        
    }
    
}
