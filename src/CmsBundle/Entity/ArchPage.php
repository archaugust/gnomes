<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page")
 */
class ArchPage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="text")
     */
    private $name;
    
    /**
     * @ORM\Column(type="text")
     */
    private $handle;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $core;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $banner;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $banner_text;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $banner_text_color;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $banner_overlay;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hits;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_description;
    

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
     * @return ArchPage
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
     * @return ArchPage
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
     * Set content
     *
     * @param string $content
     *
     * @return ArchPage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set core
     *
     * @param boolean $core
     *
     * @return ArchPage
     */
    public function setCore($core)
    {
        $this->core = $core;

        return $this;
    }

    /**
     * Get core
     *
     * @return boolean
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * Set banner
     *
     * @param string $banner
     *
     * @return ArchPage
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Get banner
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set bannerText
     *
     * @param string $bannerText
     *
     * @return ArchPage
     */
    public function setBannerText($bannerText)
    {
        $this->banner_text = $bannerText;

        return $this;
    }

    /**
     * Get bannerText
     *
     * @return string
     */
    public function getBannerText()
    {
        return $this->banner_text;
    }

    /**
     * Set bannerTextColor
     *
     * @param boolean $bannerTextColor
     *
     * @return ArchPage
     */
    public function setBannerTextColor($bannerTextColor)
    {
        $this->banner_text_color = $bannerTextColor;

        return $this;
    }

    /**
     * Get bannerTextColor
     *
     * @return boolean
     */
    public function getBannerTextColor()
    {
        return $this->banner_text_color;
    }

    /**
     * Set bannerOverlay
     *
     * @param boolean $bannerOverlay
     *
     * @return ArchPage
     */
    public function setBannerOverlay($bannerOverlay)
    {
        $this->banner_overlay = $bannerOverlay;

        return $this;
    }

    /**
     * Get bannerOverlay
     *
     * @return boolean
     */
    public function getBannerOverlay()
    {
        return $this->banner_overlay;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return ArchPage
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

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return ArchPage
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
     * @param integer $metaDescription
     *
     * @return ArchPage
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return integer
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }
}
