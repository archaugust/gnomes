<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_rental")
 */
class ArchPageRental
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
    private $gear_intro;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $faq;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hits;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pdf;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_description;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchPageRentalSection", mappedBy="page")
     */
    private $sections;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchPageRentalPrice", mappedBy="page")
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
     * @return ArchPageRental
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
     * @return ArchPageRental
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
     * @return ArchPageRental
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
     * @return ArchPageRental
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
     * @return ArchPageRental
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
     * Set gearIntro
     *
     * @param string $gearIntro
     *
     * @return ArchPageRental
     */
    public function setGearIntro($gearIntro)
    {
        $this->gear_intro = $gearIntro;

        return $this;
    }

    /**
     * Get gearIntro
     *
     * @return string
     */
    public function getGearIntro()
    {
        return $this->gear_intro;
    }

    /**
     * Set faq
     *
     * @param string $faq
     *
     * @return ArchPageRental
     */
    public function setFaq($faq)
    {
        $this->faq = $faq;

        return $this;
    }

    /**
     * Get faq
     *
     * @return string
     */
    public function getFaq()
    {
        return $this->faq;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return ArchPageRental
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
     * @return ArchPageRental
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
     * @return ArchPageRental
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
     * @param \CmsBundle\Entity\ArchPageRentalSection $section
     *
     * @return ArchPageRental
     */
    public function addSection(\CmsBundle\Entity\ArchPageRentalSection $section)
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * Remove section
     *
     * @param \CmsBundle\Entity\ArchPageRentalSection $section
     */
    public function removeSection(\CmsBundle\Entity\ArchPageRentalSection $section)
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
     * @param \CmsBundle\Entity\ArchPageRentalPrice $price
     *
     * @return ArchPageRental
     */
    public function addPrice(\CmsBundle\Entity\ArchPageRentalPrice $price)
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \CmsBundle\Entity\ArchPageRentalPrice $price
     */
    public function removePrice(\CmsBundle\Entity\ArchPageRentalPrice $price)
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

    /**
     * Set pdf
     *
     * @param string $pdf
     *
     * @return ArchPageRental
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf
     *
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }
}
