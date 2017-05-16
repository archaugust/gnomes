<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_ski_finder")
 */
class ArchSkiFinder
{
    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="ArchProduct")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $product;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $technical_ability;
    
    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $experience;
    
    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $approach;
    
    /**
     * @ORM\Column(type="simple_array", length=15, nullable=true)
     */
    private $favourite_hangouts;
    
    /**
     * @ORM\Column(type="simple_array", length=15, nullable=true)
     */
    private $favourite_flavour;
    
    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $specialist;
    
    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $fat_or_skinny;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return ArchSkiFinder
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
     * Set technicalAbility
     *
     * @param array $technicalAbility
     *
     * @return ArchSkiFinder
     */
    public function setTechnicalAbility($technicalAbility)
    {
        $this->technical_ability = $technicalAbility;

        return $this;
    }

    /**
     * Get technicalAbility
     *
     * @return array
     */
    public function getTechnicalAbility()
    {
        return $this->technical_ability;
    }

    /**
     * Set experience
     *
     * @param array $experience
     *
     * @return ArchSkiFinder
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return array
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set approach
     *
     * @param array $approach
     *
     * @return ArchSkiFinder
     */
    public function setApproach($approach)
    {
        $this->approach = $approach;

        return $this;
    }

    /**
     * Get approach
     *
     * @return array
     */
    public function getApproach()
    {
        return $this->approach;
    }

    /**
     * Set favouriteHangouts
     *
     * @param array $favouriteHangouts
     *
     * @return ArchSkiFinder
     */
    public function setFavouriteHangouts($favouriteHangouts)
    {
        $this->favourite_hangouts = $favouriteHangouts;

        return $this;
    }

    /**
     * Get favouriteHangouts
     *
     * @return array
     */
    public function getFavouriteHangouts()
    {
        return $this->favourite_hangouts;
    }

    /**
     * Set favouriteFlavour
     *
     * @param array $favouriteFlavour
     *
     * @return ArchSkiFinder
     */
    public function setFavouriteFlavour($favouriteFlavour)
    {
        $this->favourite_flavour = $favouriteFlavour;

        return $this;
    }

    /**
     * Get favouriteFlavour
     *
     * @return array
     */
    public function getFavouriteFlavour()
    {
        return $this->favourite_flavour;
    }

    /**
     * Set specialist
     *
     * @param array $specialist
     *
     * @return ArchSkiFinder
     */
    public function setSpecialist($specialist)
    {
        $this->specialist = $specialist;

        return $this;
    }

    /**
     * Get specialist
     *
     * @return array
     */
    public function getSpecialist()
    {
        return $this->specialist;
    }

    /**
     * Set fatOrSkinny
     *
     * @param array $fatOrSkinny
     *
     * @return ArchSkiFinder
     */
    public function setFatOrSkinny($fatOrSkinny)
    {
        $this->fat_or_skinny = $fatOrSkinny;

        return $this;
    }

    /**
     * Get fatOrSkinny
     *
     * @return array
     */
    public function getFatOrSkinny()
    {
        return $this->fat_or_skinny;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\ArchProduct $product
     *
     * @return ArchSkiFinder
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
}
