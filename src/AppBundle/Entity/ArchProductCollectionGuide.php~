<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_collection_guide")
 */
class ArchProductCollectionGuide
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
     * @ORM\Column(type="text")
     */
    private $name;
    
    /**
     * @ORM\Column(type="text")
     */
    private $intro;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchProductCollectionGuideStep", mappedBy="guide")
     */
    private $steps;

    public function __construct() {
    	$this->steps = new ArrayCollection();
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
     * Set collectionId
     *
     * @param integer $collectionId
     *
     * @return ArchProductCollectionGuide
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
     * Set name
     *
     * @param string $name
     *
     * @return ArchProductCollectionGuide
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
     * Set intro
     *
     * @param string $intro
     *
     * @return ArchProductCollectionGuide
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set collection
     *
     * @param \AppBundle\Entity\ArchProductCollection $collection
     *
     * @return ArchProductCollectionGuide
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
     * Add step
     *
     * @param \AppBundle\Entity\ArchProductCollectionGuideStep $step
     *
     * @return ArchProductCollectionGuide
     */
    public function addStep(\AppBundle\Entity\ArchProductCollectionGuideStep $step)
    {
        $this->steps[] = $step;

        return $this;
    }

    /**
     * Remove step
     *
     * @param \AppBundle\Entity\ArchProductCollectionGuideStep $step
     */
    public function removeStep(\AppBundle\Entity\ArchProductCollectionGuideStep $step)
    {
        $this->steps->removeElement($step);
    }

    /**
     * Get steps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSteps()
    {
        return $this->steps;
    }
}
