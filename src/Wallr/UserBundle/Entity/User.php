<?php
// src/Acme/UserBundle/Entity/User.php

namespace Wallr\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    /**
    * @ORM\OneToMany(targetEntity="Wallr\APIBundle\Entity\Feed", mappedBy="user")
    */
    private $feeds;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname;
 
    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;
 
    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    protected $facebookId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="twitterID", type="string", length=255, nullable=true)
     */
    protected $twitterID;
 
    public function serialize()
    {
        return serialize(array($this->facebookId, parent::serialize()));
    }
 
    public function unserialize($data)
    {
        list($this->facebookId, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }
 
    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
 
    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
 
    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }
 
    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
 
    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
 
    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        $this->setUsername($facebookId);
        $this->salt = '';
    }
 
    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }
 
    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setLastname($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
        if (isset($fbdata['username'])) {
            $this->setUsername( $fbdata['username'] );
        }
    }
    
    /**
     * Set twitterID
     *
     * @param string $twitterID
     */
    public function setTwitterID($twitterID)
    {
        $this->twitterID = $twitterID;
        $this->setUsername($twitterID);
        $this->salt = '';
    }

    /**
     * Get twitterID
     *
     * @return string 
     */
    public function getTwitterID()
    {
        return $this->twitterID;
    }

    /**
     * Set twitter_username
     *
     * @param string $twitterUsername
     */
    public function setTwitterUsername($twitterUsername)
    {
        $this->twitter_username = $twitterUsername;
    }

    /**
     * Get twitter_username
     *
     * @return string 
     */
    public function getTwitterUsername()
    {
        return $this->twitter_username;
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add feeds
     *
     * @param \Wallr\APIBundle\Entity\Feed $feeds
     * @return User
     */
    public function addFeed(\Wallr\APIBundle\Entity\Feed $feeds)
    {
        $this->feeds[] = $feeds;
    
        return $this;
    }

    /**
     * Remove feeds
     *
     * @param \Wallr\APIBundle\Entity\Feed $feeds
     */
    public function removeFeed(\Wallr\APIBundle\Entity\Feed $feeds)
    {
        $this->feeds->removeElement($feeds);
    }

    /**
     * Get feeds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFeeds()
    {
        return $this->feeds;
    }
}