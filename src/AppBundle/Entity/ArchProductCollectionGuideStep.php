<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_collection_guide_step")
 */
class ArchProductCollectionGuideStep
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
    private $guide_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchProductCollectionGuide")
     * @ORM\JoinColumn(name="guide_id", referencedColumnName="id")
     */
    private $guide;
    
    /**
     * @ORM\Column(type="text")
     */
    private $name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $image;
    
    /**
     * @ORM\Column(type="text")
     */
    private $details;
    
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
     * Set guideId
     *
     * @param integer $guideId
     *
     * @return ArchProductCollectionGuideStep
     */
    public function setGuideId($guideId)
    {
        $this->guide_id = $guideId;

        return $this;
    }

    /**
     * Get guideId
     *
     * @return integer
     */
    public function getGuideId()
    {
        return $this->guide_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArchProductCollectionGuideStep
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
     * Set image
     *
     * @param string $image
     *
     * @return ArchProductCollectionGuideStep
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set details
     *
     * @param string $details
     *
     * @return ArchProductCollectionGuideStep
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set guide
     *
     * @param \AppBundle\Entity\ArchProductCollectionGuide $guide
     *
     * @return ArchProductCollectionGuideStep
     */
    public function setGuide(\AppBundle\Entity\ArchProductCollectionGuide $guide = null)
    {
        $this->guide = $guide;

        return $this;
    }

    /**
     * Get guide
     *
     * @return \AppBundle\Entity\ArchProductCollectionGuide
     */
    public function getGuide()
    {
        return $this->guide;
    }

    /**
     * Add step
     *
     * @param \AppBundle\Entity\ArchProductCollectionGuideStep $step
     *
     * @return ArchProductCollectionGuideStep
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
