<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_category_collection")
 */
class ArchProductCategoryCollection
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
    private $category_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchProductCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return ArchProductCategoryCollection
     */
    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set collectionId
     *
     * @param integer $collectionId
     *
     * @return ArchProductCategoryCollection
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
     * Set category
     *
     * @param \AppBundle\Entity\ArchProductCategory $category
     *
     * @return ArchProductCategoryCollection
     */
    public function setCategory(\AppBundle\Entity\ArchProductCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ArchProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set collection
     *
     * @param \AppBundle\Entity\ArchProductCollection $collection
     *
     * @return ArchProductCategoryCollection
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
}
