<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_country")
 */
class ArchCountry
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=2)
     */
    private $country_id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=4)
     */
    private $phone_code;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchRegion", mappedBy="country")
     */
    private $regions;
    
    public function __construct() {
    	$this->regions = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return ArchCountry
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
     * Add region
     *
     * @param \AppBundle\Entity\ArchRegion $region
     *
     * @return ArchCountry
     */
    public function addRegion(\AppBundle\Entity\ArchRegion $region)
    {
        $this->regions[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param \AppBundle\Entity\ArchRegion $region
     */
    public function removeRegion(\AppBundle\Entity\ArchRegion $region)
    {
        $this->regions->removeElement($region);
    }

    /**
     * Get regions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegions()
    {
        return $this->regions;
    }

    /**
     * Set countryId
     *
     * @param string $countryId
     *
     * @return ArchCountry
     */
    public function setCountryId($countryId)
    {
        $this->country_id = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return string
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * Set phoneCode
     *
     * @param string $phoneCode
     *
     * @return ArchCountry
     */
    public function setPhoneCode($phoneCode)
    {
        $this->phone_code = $phoneCode;

        return $this;
    }

    /**
     * Get phoneCode
     *
     * @return string
     */
    public function getPhoneCode()
    {
        return $this->phone_code;
    }
}
