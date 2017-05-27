<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_home")
 */
class ArchPageHome
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
     * @ORM\Column(type="boolean")
     */
    private $is_default;
    
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
     * @ORM\OneToMany(targetEntity="ArchPageHomeSlide", mappedBy="page")
     */
    private $slides;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchPageHomeSection", mappedBy="page")
     */
    private $sections;
    
    public function __construct() {
    	$this->is_default = 0;
    	$this->hits = 0;
    	$this->slides = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return ArchPageHome
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
     * Set isDefault
     *
     * @param boolean $isDefault
     *
     * @return ArchPageHome
     */
    public function setIsDefault($isDefault)
    {
        $this->is_default = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return ArchPageHome
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
     * @return ArchPageHome
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
     * Add slide
     *
     * @param \CmsBundle\Entity\ArchPageHomeSlide $slide
     *
     * @return ArchPageHome
     */
    public function addSlide(\CmsBundle\Entity\ArchPageHomeSlide $slide)
    {
        $this->slides[] = $slide;

        return $this;
    }

    /**
     * Remove slide
     *
     * @param \CmsBundle\Entity\ArchPageHomeSlide $slide
     */
    public function removeSlide(\CmsBundle\Entity\ArchPageHomeSlide $slide)
    {
        $this->slides->removeElement($slide);
    }

    /**
     * Get slides
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * Add section
     *
     * @param \CmsBundle\Entity\ArchPageHomeSection $section
     *
     * @return ArchPageHome
     */
    public function addSection(\CmsBundle\Entity\ArchPageHomeSection $section)
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * Remove section
     *
     * @param \CmsBundle\Entity\ArchPageHomeSection $section
     */
    public function removeSection(\CmsBundle\Entity\ArchPageHomeSection $section)
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
     * Set hits
     *
     * @param integer $hits
     *
     * @return ArchPageHome
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
