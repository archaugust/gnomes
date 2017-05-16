<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_blog_main")
 */
class ArchBlogMain
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
    private $header;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $intro;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $filename;
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
     * Set header
     *
     * @param string $header
     *
     * @return ArchBlogMain
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set intro
     *
     * @param string $intro
     *
     * @return ArchBlogMain
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return ArchBlogMain
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set bannerText
     *
     * @param string $bannerText
     *
     * @return ArchBlogMain
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
     * @return ArchBlogMain
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
     * @return ArchBlogMain
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
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return ArchBlogMain
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
     * @return ArchBlogMain
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
}
