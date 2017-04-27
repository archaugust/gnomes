<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product_discounter_filter")
 */
class ArchProductDiscounterFilter
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
     * Set discounterId
     *
     * @param integer $discounterId
     *
     * @return ArchProductDiscounterFilter
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
     * Set field
     *
     * @param string $field
     *
     * @return ArchProductDiscounterFilter
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
     * @return ArchProductDiscounterFilter
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
     * Set discounter
     *
     * @param \AppBundle\Entity\ArchProductDiscounter $discounter
     *
     * @return ArchProductDiscounterFilter
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
}
