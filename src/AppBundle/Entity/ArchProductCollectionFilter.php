<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_collection_filter")
 */
class ArchProductCollectionFilter
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
    private $field;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $value;

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
     * @return ArchProductCollectionFilter
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
     * Set field
     *
     * @param string $field
     *
     * @return ArchProductCollectionFilter
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return ArchProductCollectionFilter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set collection
     *
     * @param \AppBundle\Entity\ArchProductCollection $collection
     *
     * @return ArchProductCollectionFilter
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
