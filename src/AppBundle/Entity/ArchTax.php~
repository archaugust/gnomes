<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_tax")
 */
class ArchTax
{
    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    
    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $rate;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $is_default;

    public function getNameRateLabel()
    {
    	return $this->name .' ('. $this->rate * 100 .'%)';
    }
    
    public function getNameRateValue()
    {
    	return $this->name .':'. $this->rate;
    }
    
    /**
     * Set id
     *
     * @param string $id
     *
     * @return ArchTax
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
     * Set name
     *
     * @param string $name
     *
     * @return ArchTax
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
     * Set rate
     *
     * @param string $rate
     *
     * @return ArchTax
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ArchTax
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     *
     * @return ArchTax
     */
    public function setIsDefault($isDefault)
    {
        $this->is_default = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }
}
