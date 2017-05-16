<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_blog")
 */
class ArchBlog
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $teaser;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $handle;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $image_main;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image_main_caption;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_main;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $image_middle;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image_middle_caption;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_bottom_title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_bottom;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_description;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $display_date;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $updated_at;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $hits;
    
    public function __construct() {
    	$this->hits = 0;
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
     * Set name
     *
     * @param string $name
     *
     * @return ArchBlog
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
     * Set handle
     *
     * @param string $handle
     *
     * @return ArchBlog
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * Get handle
     *
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * Set imageMain
     *
     * @param string $imageMain
     *
     * @return ArchBlog
     */
    public function setImageMain($imageMain)
    {
        $this->image_main = $imageMain;

        return $this;
    }

    /**
     * Get imageMain
     *
     * @return string
     */
    public function getImageMain()
    {
        return $this->image_main;
    }

    /**
     * Set imageMainCaption
     *
     * @param string $imageMainCaption
     *
     * @return ArchBlog
     */
    public function setImageMainCaption($imageMainCaption)
    {
        $this->image_main_caption = $imageMainCaption;

        return $this;
    }

    /**
     * Get imageMainCaption
     *
     * @return string
     */
    public function getImageMainCaption()
    {
        return $this->image_main_caption;
    }

    /**
     * Set contentMain
     *
     * @param string $contentMain
     *
     * @return ArchBlog
     */
    public function setContentMain($contentMain)
    {
        $this->content_main = $contentMain;

        return $this;
    }

    /**
     * Get contentMain
     *
     * @return string
     */
    public function getContentMain()
    {
        return $this->content_main;
    }

    /**
     * Set imageMiddle
     *
     * @param string $imageMiddle
     *
     * @return ArchBlog
     */
    public function setImageMiddle($imageMiddle)
    {
        $this->image_middle = $imageMiddle;

        return $this;
    }

    /**
     * Get imageMiddle
     *
     * @return string
     */
    public function getImageMiddle()
    {
        return $this->image_middle;
    }

    /**
     * Set imageMiddleCaption
     *
     * @param string $imageMiddleCaption
     *
     * @return ArchBlog
     */
    public function setImageMiddleCaption($imageMiddleCaption)
    {
        $this->image_middle_caption = $imageMiddleCaption;

        return $this;
    }

    /**
     * Get imageMiddleCaption
     *
     * @return string
     */
    public function getImageMiddleCaption()
    {
        return $this->image_middle_caption;
    }

    /**
     * Set contentBottomTitle
     *
     * @param string $contentBottomTitle
     *
     * @return ArchBlog
     */
    public function setContentBottomTitle($contentBottomTitle)
    {
        $this->content_bottom_title = $contentBottomTitle;

        return $this;
    }

    /**
     * Get contentBottomTitle
     *
     * @return string
     */
    public function getContentBottomTitle()
    {
        return $this->content_bottom_title;
    }

    /**
     * Set contentBottom
     *
     * @param string $contentBottom
     *
     * @return ArchBlog
     */
    public function setContentBottom($contentBottom)
    {
        $this->content_bottom = $contentBottom;

        return $this;
    }

    /**
     * Get contentBottom
     *
     * @return string
     */
    public function getContentBottom()
    {
        return $this->content_bottom;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ArchBlog
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return ArchBlog
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return ArchBlog
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set displayDate
     *
     * @param \DateTime $displayDate
     *
     * @return ArchBlog
     */
    public function setDisplayDate($displayDate)
    {
        $this->display_date = $displayDate;

        return $this;
    }

    /**
     * Get displayDate
     *
     * @return \DateTime
     */
    public function getDisplayDate()
    {
        return $this->display_date;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ArchBlog
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set teaser
     *
     * @param string $teaser
     *
     * @return ArchBlog
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return ArchBlog
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }
}
