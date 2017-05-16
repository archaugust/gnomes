<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_order")
 */
class ArchOrder
{
    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $customer;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $user;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sale_date;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $total_price;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $total_tax;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $tax_name;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $short_code;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $invoice_number;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auth_code;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $card_holder_name;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $client_info;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchOrderProduct", mappedBy="order")
     */
    private $products;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shipping_id;
    
    /**
     * @ORM\OneToOne(targetEntity="ArchOrderShipping")
     * @ORM\JoinColumn(name="shipping_id", referencedColumnName="id")
     */
    private $shipping;
    
    public function __construct()
    {
    	$this->products = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return ArchOrder
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return ArchOrder
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return ArchOrder
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set saleDate
     *
     * @param \DateTime $saleDate
     *
     * @return ArchOrder
     */
    public function setSaleDate($saleDate)
    {
        $this->sale_date = $saleDate;

        return $this;
    }

    /**
     * Get saleDate
     *
     * @return \DateTime
     */
    public function getSaleDate()
    {
        return $this->sale_date;
    }

    /**
     * Set totalPrice
     *
     * @param string $totalPrice
     *
     * @return ArchOrder
     */
    public function setTotalPrice($totalPrice)
    {
        $this->total_price = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return string
     */
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    /**
     * Set totalTax
     *
     * @param string $totalTax
     *
     * @return ArchOrder
     */
    public function setTotalTax($totalTax)
    {
        $this->total_tax = $totalTax;

        return $this;
    }

    /**
     * Get totalTax
     *
     * @return string
     */
    public function getTotalTax()
    {
        return $this->total_tax;
    }

    /**
     * Set taxName
     *
     * @param string $taxName
     *
     * @return ArchOrder
     */
    public function setTaxName($taxName)
    {
        $this->tax_name = $taxName;

        return $this;
    }

    /**
     * Get taxName
     *
     * @return string
     */
    public function getTaxName()
    {
        return $this->tax_name;
    }

    /**
     * Set shortCode
     *
     * @param string $shortCode
     *
     * @return ArchOrder
     */
    public function setShortCode($shortCode)
    {
        $this->short_code = $shortCode;

        return $this;
    }

    /**
     * Get shortCode
     *
     * @return string
     */
    public function getShortCode()
    {
        return $this->short_code;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return ArchOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set invoiceNumber
     *
     * @param integer $invoiceNumber
     *
     * @return ArchOrder
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoice_number = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return integer
     */
    public function getInvoiceNumber()
    {
        return $this->invoice_number;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return ArchOrder
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set authCode
     *
     * @param string $authCode
     *
     * @return ArchOrder
     */
    public function setAuthCode($authCode)
    {
        $this->auth_code = $authCode;

        return $this;
    }

    /**
     * Get authCode
     *
     * @return string
     */
    public function getAuthCode()
    {
        return $this->auth_code;
    }

    /**
     * Set cardHolderName
     *
     * @param string $cardHolderName
     *
     * @return ArchOrder
     */
    public function setCardHolderName($cardHolderName)
    {
        $this->card_holder_name = $cardHolderName;

        return $this;
    }

    /**
     * Get cardHolderName
     *
     * @return string
     */
    public function getCardHolderName()
    {
        return $this->card_holder_name;
    }

    /**
     * Set clientInfo
     *
     * @param string $clientInfo
     *
     * @return ArchOrder
     */
    public function setClientInfo($clientInfo)
    {
        $this->client_info = $clientInfo;

        return $this;
    }

    /**
     * Get clientInfo
     *
     * @return string
     */
    public function getClientInfo()
    {
        return $this->client_info;
    }

    /**
     * Set shippingId
     *
     * @param integer $shippingId
     *
     * @return ArchOrder
     */
    public function setShippingId($shippingId)
    {
        $this->shipping_id = $shippingId;

        return $this;
    }

    /**
     * Get shippingId
     *
     * @return integer
     */
    public function getShippingId()
    {
        return $this->shipping_id;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\User $customer
     *
     * @return ArchOrder
     */
    public function setCustomer(\AppBundle\Entity\User $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\User
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\ArchOrderProduct $product
     *
     * @return ArchOrder
     */
    public function addProduct(\AppBundle\Entity\ArchOrderProduct $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\ArchOrderProduct $product
     */
    public function removeProduct(\AppBundle\Entity\ArchOrderProduct $product)
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

    /**
     * Set shipping
     *
     * @param \AppBundle\Entity\ArchOrderShipping $shipping
     *
     * @return ArchOrder
     */
    public function setShipping(\AppBundle\Entity\ArchOrderShipping $shipping = null)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * Get shipping
     *
     * @return \AppBundle\Entity\ArchOrderShipping
     */
    public function getShipping()
    {
        return $this->shipping;
    }
}
