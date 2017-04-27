<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_discounter")
 */
class ArchProductDiscounter
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $rate;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $suffix;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchProductDiscounterFilter", mappedBy="discounter")
     */
    private $filters;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchProductDiscounterProduct", mappedBy="discounter")
     */
    private $products;
    
    public function __construct() {
    	$this->is_active = 0;
    	$this->filters = new ArrayCollection();
    	$this->products = new ArrayCollection();
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
     * @return ArchProductDiscounter
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
     * Set rate
     *
     * @param string $rate
     *
     * @return ArchProductDiscounter
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set suffix
     *
     * @param integer $suffix
     *
     * @return ArchProductDiscounter
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Get suffix
     *
     * @return integer
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ArchProductDiscounter
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
     * Add filter
     *
     * @param \AppBundle\Entity\ArchProductDiscounterFilter $filter
     *
     * @return ArchProductDiscounter
     */
    public function addFilter(\AppBundle\Entity\ArchProductDiscounterFilter $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Remove filter
     *
     * @param \AppBundle\Entity\ArchProductDiscounterFilter $filter
     */
    public function removeFilter(\AppBundle\Entity\ArchProductDiscounterFilter $filter)
    {
        $this->filters->removeElement($filter);
    }

    /**
     * Get filters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\ArchProductDiscounterProduct $product
     *
     * @return ArchProductDiscounter
     */
    public function addProduct(\AppBundle\Entity\ArchProductDiscounterProduct $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\ArchProductDiscounterProduct $product
     */
    public function removeProduct(\AppBundle\Entity\ArchProductDiscounterProduct $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
