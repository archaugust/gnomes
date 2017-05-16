<?php
namespace CmsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_rental_booking_guest")
 */
class ArchRentalBookingGuest
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
    private $booking_id;

    /**
     * @ORM\ManyToOne(targetEntity="ArchRentalBooking", inversedBy="guests")
     * @ORM\JoinColumn(name="booking_id", referencedColumnName="id")
     */
    private $booking;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $first_name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $last_name;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $height;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $weight;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $shoe_size;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;
    
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $ability_level;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $gear;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $gear_type;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $snowboard_stance;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $jacket;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $pants;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $goggles;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $gloves;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $helmet;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $chains;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $instructions;

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
     * Set bookingId
     *
     * @param integer $bookingId
     *
     * @return ArchRentalBookingGuest
     */
    public function setBookingId($bookingId)
    {
        $this->booking_id = $bookingId;

        return $this;
    }

    /**
     * Get bookingId
     *
     * @return integer
     */
    public function getBookingId()
    {
        return $this->booking_id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return ArchRentalBookingGuest
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return ArchRentalBookingGuest
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return ArchRentalBookingGuest
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return ArchRentalBookingGuest
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set shoeSize
     *
     * @param string $shoeSize
     *
     * @return ArchRentalBookingGuest
     */
    public function setShoeSize($shoeSize)
    {
        $this->shoe_size = $shoeSize;

        return $this;
    }

    /**
     * Get shoeSize
     *
     * @return string
     */
    public function getShoeSize()
    {
        return $this->shoe_size;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return ArchRentalBookingGuest
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set abilityLevel
     *
     * @param string $abilityLevel
     *
     * @return ArchRentalBookingGuest
     */
    public function setAbilityLevel($abilityLevel)
    {
        $this->ability_level = $abilityLevel;

        return $this;
    }

    /**
     * Get abilityLevel
     *
     * @return string
     */
    public function getAbilityLevel()
    {
        return $this->ability_level;
    }

    /**
     * Set gear
     *
     * @param string $gear
     *
     * @return ArchRentalBookingGuest
     */
    public function setGear($gear)
    {
        $this->gear = $gear;

        return $this;
    }

    /**
     * Get gear
     *
     * @return string
     */
    public function getGear()
    {
        return $this->gear;
    }

    /**
     * Set gearType
     *
     * @param string $gearType
     *
     * @return ArchRentalBookingGuest
     */
    public function setGearType($gearType)
    {
        $this->gear_type = $gearType;

        return $this;
    }

    /**
     * Get gearType
     *
     * @return string
     */
    public function getGearType()
    {
        return $this->gear_type;
    }

    /**
     * Set snowboardStance
     *
     * @param string $snowboardStance
     *
     * @return ArchRentalBookingGuest
     */
    public function setSnowboardStance($snowboardStance)
    {
        $this->snowboard_stance = $snowboardStance;

        return $this;
    }

    /**
     * Get snowboardStance
     *
     * @return string
     */
    public function getSnowboardStance()
    {
        return $this->snowboard_stance;
    }

    /**
     * Set jacket
     *
     * @param string $jacket
     *
     * @return ArchRentalBookingGuest
     */
    public function setJacket($jacket)
    {
        $this->jacket = $jacket;

        return $this;
    }

    /**
     * Get jacket
     *
     * @return string
     */
    public function getJacket()
    {
        return $this->jacket;
    }

    /**
     * Set pants
     *
     * @param string $pants
     *
     * @return ArchRentalBookingGuest
     */
    public function setPants($pants)
    {
        $this->pants = $pants;

        return $this;
    }

    /**
     * Get pants
     *
     * @return string
     */
    public function getPants()
    {
        return $this->pants;
    }

    /**
     * Set goggles
     *
     * @param string $goggles
     *
     * @return ArchRentalBookingGuest
     */
    public function setGoggles($goggles)
    {
        $this->goggles = $goggles;

        return $this;
    }

    /**
     * Get goggles
     *
     * @return string
     */
    public function getGoggles()
    {
        return $this->goggles;
    }

    /**
     * Set gloves
     *
     * @param string $gloves
     *
     * @return ArchRentalBookingGuest
     */
    public function setGloves($gloves)
    {
        $this->gloves = $gloves;

        return $this;
    }

    /**
     * Get gloves
     *
     * @return string
     */
    public function getGloves()
    {
        return $this->gloves;
    }

    /**
     * Set helmet
     *
     * @param string $helmet
     *
     * @return ArchRentalBookingGuest
     */
    public function setHelmet($helmet)
    {
        $this->helmet = $helmet;

        return $this;
    }

    /**
     * Get helmet
     *
     * @return string
     */
    public function getHelmet()
    {
        return $this->helmet;
    }

    /**
     * Set chains
     *
     * @param string $chains
     *
     * @return ArchRentalBookingGuest
     */
    public function setChains($chains)
    {
        $this->chains = $chains;

        return $this;
    }

    /**
     * Get chains
     *
     * @return string
     */
    public function getChains()
    {
        return $this->chains;
    }

    /**
     * Set instructions
     *
     * @param string $instructions
     *
     * @return ArchRentalBookingGuest
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set booking
     *
     * @param \CmsBundle\Entity\ArchRentalBooking $booking
     *
     * @return ArchRentalBookingGuest
     */
    public function setBooking(\CmsBundle\Entity\ArchRentalBooking $booking = null)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \CmsBundle\Entity\ArchRentalBooking
     */
    public function getBooking()
    {
        return $this->booking;
    }
}
