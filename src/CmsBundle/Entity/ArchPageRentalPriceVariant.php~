<?php
namespace CmsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_rental_price_variant")
 */
class ArchPageRentalPriceVariant
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
    private $price_id;

    /**
     * @ORM\ManyToOne(targetEntity="ArchPageRentalPrice", inversedBy="variants")
     * @ORM\JoinColumn(name="price_id", referencedColumnName="id")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $prices;

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
     * Set priceId
     *
     * @param integer $priceId
     *
     * @return ArchPageRentalPriceVariant
     */
    public function setPriceId($priceId)
    {
        $this->price_id = $priceId;

        return $this;
    }

    /**
     * Get priceId
     *
     * @return integer
     */
    public function getPriceId()
    {
        return $this->price_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArchPageRentalPriceVariant
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
     * Set prices
     *
     * @param string $prices
     *
     * @return ArchPageRentalPriceVariant
     */
    public function setPrices($prices)
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * Get prices
     *
     * @return string
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * Set price
     *
     * @param \CmsBundle\Entity\ArchPageRentalPrice $price
     *
     * @return ArchPageRentalPriceVariant
     */
    public function setPrice(\CmsBundle\Entity\ArchPageRentalPrice $price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return \CmsBundle\Entity\ArchPageRentalPrice
     */
    public function getPrice()
    {
        return $this->price;
    }
}
