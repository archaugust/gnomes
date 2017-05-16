<?php
namespace CmsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_rental_price")
 */
class ArchPageRentalPrice
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $page_id;

    /**
     * @ORM\ManyToOne(targetEntity="ArchPageRental", inversedBy="prices")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $items;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchPageRentalPriceVariant", mappedBy="price")
     */
    private $variants;
    
    public function __construct() {
    	$this->variants = new ArrayCollection();
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
     * Set pageId
     *
     * @param integer $pageId
     *
     * @return ArchPageRentalPrice
     */
    public function setPageId($pageId)
    {
        $this->page_id = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArchPageRentalPrice
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
     * Set items
     *
     * @param string $items
     *
     * @return ArchPageRentalPrice
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return string
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set page
     *
     * @param \CmsBundle\Entity\ArchPageRental $page
     *
     * @return ArchPageRentalPrice
     */
    public function setPage(\CmsBundle\Entity\ArchPageRental $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \CmsBundle\Entity\ArchPageRental
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add variant
     *
     * @param \CmsBundle\Entity\ArchPageRentalPriceVariant $variant
     *
     * @return ArchPageRentalPrice
     */
    public function addVariant(\CmsBundle\Entity\ArchPageRentalPriceVariant $variant)
    {
        $this->variants[] = $variant;

        return $this;
    }

    /**
     * Remove variant
     *
     * @param \CmsBundle\Entity\ArchPageRentalPriceVariant $variant
     */
    public function removeVariant(\CmsBundle\Entity\ArchPageRentalPriceVariant $variant)
    {
        $this->variants->removeElement($variant);
    }

    /**
     * Get variants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariants()
    {
        return $this->variants;
    }
}
