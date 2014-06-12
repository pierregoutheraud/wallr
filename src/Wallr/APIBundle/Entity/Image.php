<?php

namespace Wallr\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wallr\APIBundle\Entity\ImageRepository")
 */
class Image
{
    
    /**
    * @ORM\ManyToOne(targetEntity="Wallr\APIBundle\Entity\Feed", inversedBy="images" )
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $feed;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="text", nullable=true)
     */
    private $link;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isread", type="boolean")
     */
    private $isread = false;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rand", type="integer")
     */
    private $rand;

    public function __construct()
    {
        $this->rand = rand(0, 1000);
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
     * Set url
     *
     * @param string $url
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set isread
     *
     * @param boolean $isread
     * @return Image
     */
    public function setIsread($isread)
    {
        $this->isread = $isread;
    
        return $this;
    }

    /**
     * Get isread
     *
     * @return boolean 
     */
    public function getIsread()
    {
        return $this->isread;
    }

    /**
     * Set feed
     *
     * @param \Wallr\APIBundle\Entity\Feed $feed
     * @return Image
     */
    public function setFeed(\Wallr\APIBundle\Entity\Feed $feed)
    {
        $this->feed = $feed;
    
        return $this;
    }

    /**
     * Get feed
     *
     * @return \Wallr\APIBundle\Entity\Feed 
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Image
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Image
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set rand
     *
     * @param integer $rand
     * @return Image
     */
    public function setRand($rand)
    {
        $this->rand = $rand;
    
        return $this;
    }

    /**
     * Get rand
     *
     * @return integer 
     */
    public function getRand()
    {
        return $this->rand;
    }
}