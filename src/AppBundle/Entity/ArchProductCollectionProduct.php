<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_collection_product")
 */
class ArchProductCollectionProduct
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
    private $collection_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchProductCollection")
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     */
    private $collection;
    
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $new;
    
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
     * Set collectionId
     *
     * @param integer $collectionId
     *
     * @return ArchProductCollectionProduct
     */
    public function setCollectionId($collectionId)
    {
        $this->collection_id = $collectionId;

        return $this;
    }

    /**
     * Get collectionId
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->collection_id;
    }

    /**
     * Set productId
     *
     * @param string $productId
     *
     * @return ArchProductCollectionProduct
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
     * Set collection
     *
     * @param \AppBundle\Entity\ArchProductCollection $collection
     *
     * @return ArchProductCollectionProduct
     */
    public function setCollection(\AppBundle\Entity\ArchProductCollection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \AppBundle\Entity\ArchProductCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\ArchProduct $product
     *
     * @return ArchProductCollectionProduct
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
     * Set new
     *
     * @param boolean $new
     *
     * @return ArchProductCollectionProduct
     */
    public function setNew($new)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * Get new
     *
     * @return boolean
     */
    public function getNew()
    {
        return $this->new;
    }
}
