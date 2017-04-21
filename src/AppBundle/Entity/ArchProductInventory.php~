<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_inventory")
 */
class ArchProductInventory
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $product_id;
	
    /**
     * @ORM\ManyToOne(targetEntity="ArchProduct", inversedBy="inventory")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $outlet_id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $count;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $reorder_point;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $restock_level;

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
     * Set productId
     *
     * @param string $productId
     *
     * @return ArchProductInventory
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
     * Set outletId
     *
     * @param string $outletId
     *
     * @return ArchProductInventory
     */
    public function setOutletId($outletId)
    {
        $this->outlet_id = $outletId;

        return $this;
    }

    /**
     * Get outletId
     *
     * @return string
     */
    public function getOutletId()
    {
        return $this->outlet_id;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return ArchProductInventory
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set reorderPoint
     *
     * @param integer $reorderPoint
     *
     * @return ArchProductInventory
     */
    public function setReorderPoint($reorderPoint)
    {
        $this->reorder_point = $reorderPoint;

        return $this;
    }

    /**
     * Get reorderPoint
     *
     * @return integer
     */
    public function getReorderPoint()
    {
        return $this->reorder_point;
    }

    /**
     * Set restockLevel
     *
     * @param integer $restockLevel
     *
     * @return ArchProductInventory
     */
    public function setRestockLevel($restockLevel)
    {
        $this->restock_level = $restockLevel;

        return $this;
    }

    /**
     * Get restockLevel
     *
     * @return integer
     */
    public function getRestockLevel()
    {
        return $this->restock_level;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\ArchProduct $product
     *
     * @return ArchProductInventory
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
     * Set outlet
     *
     * @param \AppBundle\Entity\ArchOutlet $outlet
     *
     * @return ArchProductInventory
     */
    public function setOutlet(\AppBundle\Entity\ArchOutlet $outlet = null)
    {
        $this->outlet = $outlet;

        return $this;
    }

    /**
     * Get outlet
     *
     * @return \AppBundle\Entity\ArchOutlet
     */
    public function getOutlet()
    {
        return $this->outlet;
    }
}
