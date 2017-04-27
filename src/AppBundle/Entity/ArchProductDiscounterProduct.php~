<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_discounter_product")
 */
class ArchProductDiscounterProduct
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
    private $discounter_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchProductDiscounter")
     * @ORM\JoinColumn(name="discounter_id", referencedColumnName="id")
     */
    private $discounter;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $product_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchProduct")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

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
     * Set discounterId
     *
     * @param integer $discounterId
     *
     * @return ArchProductDiscounterProduct
     */
    public function setDiscounterId($discounterId)
    {
        $this->discounter_id = $discounterId;

        return $this;
    }

    /**
     * Get discounterId
     *
     * @return integer
     */
    public function getDiscounterId()
    {
        return $this->discounter_id;
    }

    /**
     * Set productId
     *
     * @param string $productId
     *
     * @return ArchProductDiscounterProduct
     */
    public function setProductId($productId)
    {
        $this->product_id = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set discounter
     *
     * @param \AppBundle\Entity\ArchProductDiscounter $discounter
     *
     * @return ArchProductDiscounterProduct
     */
    public function setDiscounter(\AppBundle\Entity\ArchProductDiscounter $discounter = null)
    {
        $this->discounter = $discounter;

        return $this;
    }

    /**
     * Get discounter
     *
     * @return \AppBundle\Entity\ArchProductDiscounter
     */
    public function getDiscounter()
    {
        return $this->discounter;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\ArchProduct $product
     *
     * @return ArchProductDiscounterProduct
     */
    public function setProduct(\AppBundle\Entity\ArchProduct $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\ArchProduct
     */
    public function getProduct()
    {
        return $this->product;
    }
}
