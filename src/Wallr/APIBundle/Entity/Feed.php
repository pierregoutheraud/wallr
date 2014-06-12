<?php

namespace Wallr\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feed
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wallr\APIBundle\Entity\FeedRepository")
 */
class Feed
{
    
    /**
    * @ORM\ManyToOne(targetEntity="Wallr\UserBundle\Entity\User", inversedBy="feeds" )
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;
    
    //    * @ORM\OrderBy({"id" = "DESC"})
    /**
    * @ORM\OneToMany(targetEntity="Wallr\APIBundle\Entity\Image", mappedBy="feed")
    */
    private $images;
    
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
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source = 'rss';
    
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
     * @return Feed
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
     * Set user
     *
     * @param \Wallr\UserBundle\Entity\User $user
     * @return Feed
     */
    public function setUser(\Wallr\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Wallr\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add images
     *
     * @param \Wallr\APIBundle\Entity\Image $images
     * @return Feed
     */
    public function addImage(\Wallr\APIBundle\Entity\Image $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Wallr\APIBundle\Entity\Image $images
     */
    public function removeImage(\Wallr\APIBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Feed
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Feed
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }
}