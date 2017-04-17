<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_order_product")
 */
class ArchOrderProduct
{
    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $order_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchOrder", inversedBy="products")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $product_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchProduct")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $tax;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tax_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchTax")
     * @ORM\JoinColumn(name="tax_id", referencedColumnName="id")
     */
    private $tax_map;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $tax_total;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discount;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price_total;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return ArchOrderProduct
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderId
     *
     * @param string $orderId
     *
     * @return ArchOrderProduct
     */
    public function setOrderId($orderId)
    {
        $this->order_id = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * Set productId
     *
     * @param string $productId
     *
     * @return ArchOrderProduct
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
     * Set name
     *
     * @param string $name
     *
     * @return ArchOrderProduct
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return ArchOrderProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return ArchOrderProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set tax
     *
     * @param string $tax
     *
     * @return ArchOrderProduct
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return string
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set taxId
     *
     * @param string $taxId
     *
     * @return ArchOrderProduct
     */
    public function setTaxId($taxId)
    {
        $this->tax_id = $taxId;

        return $this;
    }

    /**
     * Get taxId
     *
     * @return string
     */
    public function getTaxId()
    {
        return $this->tax_id;
    }

    /**
     * Set taxTotal
     *
     * @param string $taxTotal
     *
     * @return ArchOrderProduct
     */
    public function setTaxTotal($taxTotal)
    {
        $this->tax_total = $taxTotal;

        return $this;
    }

    /**
     * Get taxTotal
     *
     * @return string
     */
    public function getTaxTotal()
    {
        return $this->tax_total;
    }

    /**
     * Set priceTotal
     *
     * @param string $priceTotal
     *
     * @return ArchOrderProduct
     */
    public function setPriceTotal($priceTotal)
    {
        $this->price_total = $priceTotal;

        return $this;
    }

    /**
     * Get priceTotal
     *
     * @return string
     */
    public function getPriceTotal()
    {
        return $this->price_total;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\ArchOrder $order
     *
     * @return ArchOrderProduct
     */
    public function setOrder(\AppBundle\Entity\ArchOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\ArchOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\ArchProduct $product
     *
     * @return ArchOrderProduct
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

    /**
     * Set taxMap
     *
     * @param \AppBundle\Entity\ArchTax $taxMap
     *
     * @return ArchOrderProduct
     */
    public function setTaxMap(\AppBundle\Entity\ArchTax $taxMap = null)
    {
        $this->tax_map = $taxMap;

        return $this;
    }

    /**
     * Get taxMap
     *
     * @return \AppBundle\Entity\ArchTax
     */
    public function getTaxMap()
    {
        return $this->tax_map;
    }

    /**
     * Set discount
     *
     * @param string $discount
     *
     * @return ArchOrderProduct
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}
