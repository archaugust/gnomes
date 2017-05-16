<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_boot")
 */
class ArchPageBoot
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $intro;
    
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_top;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_top_image;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_middle;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_middle_image;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hits;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $booking;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_description;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchPageBootSection", mappedBy="page")
     */
    private $sections;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchPageBootPrice", mappedBy="page")
     */
    private $prices;
    
    public function __construct() {
    	$this->hits = 0;
    	$this->prices = new ArrayCollection();
    	$this->sections = new ArrayCollection();
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
     * Set intro
     *
     * @param string $intro
     *
     * @return ArchPageBoot
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
     * Set banner
     *
     * @param string $banner
     *
     * @return ArchPageBoot
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
     * @return ArchPageBoot
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
     * @return ArchPageBoot
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
     * @return ArchPageBoot
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
     * Set contentTop
     *
     * @param string $contentTop
     *
     * @return ArchPageBoot
     */
    public function setContentTop($contentTop)
    {
        $this->content_top = $contentTop;

        return $this;
    }

    /**
     * Get contentTop
     *
     * @return string
     */
    public function getContentTop()
    {
        return $this->content_top;
    }

    /**
     * Set contentTopImage
     *
     * @param string $contentTopImage
     *
     * @return ArchPageBoot
     */
    public function setContentTopImage($contentTopImage)
    {
        $this->content_top_image = $contentTopImage;

        return $this;
    }

    /**
     * Get contentTopImage
     *
     * @return string
     */
    public function getContentTopImage()
    {
        return $this->content_top_image;
    }

    /**
     * Set contentMiddle
     *
     * @param string $contentMiddle
     *
     * @return ArchPageBoot
     */
    public function setContentMiddle($contentMiddle)
    {
        $this->content_middle = $contentMiddle;

        return $this;
    }

    /**
     * Get contentMiddle
     *
     * @return string
     */
    public function getContentMiddle()
    {
        return $this->content_middle;
    }

    /**
     * Set contentMiddleImage
     *
     * @param string $contentMiddleImage
     *
     * @return ArchPageBoot
     */
    public function setContentMiddleImage($contentMiddleImage)
    {
        $this->content_middle_image = $contentMiddleImage;

        return $this;
    }

    /**
     * Get contentMiddleImage
     *
     * @return string
     */
    public function getContentMiddleImage()
    {
        return $this->content_middle_image;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return ArchPageBoot
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
     * Set booking
     *
     * @param string $booking
     *
     * @return ArchPageBoot
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return string
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return ArchPageBoot
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
     * @return ArchPageBoot
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
     * Add section
     *
     * @param \CmsBundle\Entity\ArchPageBootSection $section
     *
     * @return ArchPageBoot
     */
    public function addSection(\CmsBundle\Entity\ArchPageBootSection $section)
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * Remove section
     *
     * @param \CmsBundle\Entity\ArchPageBootSection $section
     */
    public function removeSection(\CmsBundle\Entity\ArchPageBootSection $section)
    {
        $this->sections->removeElement($section);
    }

    /**
     * Get sections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Add price
     *
     * @param \CmsBundle\Entity\ArchPageBootPrice $price
     *
     * @return ArchPageBoot
     */
    public function addPrice(\CmsBundle\Entity\ArchPageBootPrice $price)
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \CmsBundle\Entity\ArchPageBootPrice $price
     */
    public function removePrice(\CmsBundle\Entity\ArchPageBootPrice $price)
    {
        $this->prices->removeElement($price);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrices()
    {
        return $this->prices;
    }
}
