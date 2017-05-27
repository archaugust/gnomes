<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_rental_booking")
 */
class ArchRentalBooking
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $date_received;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $first_name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $last_name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $phone;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $email;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    private $collect_date;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $collect_time;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    private $return_date;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchRentalBookingGuest", mappedBy="booking")
     */
    private $guests;
    
    public function __construct() {
    	$this->guests = new ArrayCollection();
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
     * Set dateReceived
     *
     * @param integer $dateReceived
     *
     * @return ArchRentalBooking
     */
    public function setDateReceived($dateReceived)
    {
        $this->date_received = $dateReceived;

        return $this;
    }

    /**
     * Get dateReceived
     *
     * @return integer
     */
    public function getDateReceived()
    {
        return $this->date_received;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return ArchRentalBooking
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
     * @return ArchRentalBooking
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
     * Set phone
     *
     * @param string $phone
     *
     * @return ArchRentalBooking
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ArchRentalBooking
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set collectDate
     *
     * @param \DateTime $collectDate
     *
     * @return ArchRentalBooking
     */
    public function setCollectDate($collectDate)
    {
        $this->collect_date = $collectDate;

        return $this;
    }

    /**
     * Get collectDate
     *
     * @return \DateTime
     */
    public function getCollectDate()
    {
        return $this->collect_date;
    }

    /**
     * Set collectTime
     *
     * @param string $collectTime
     *
     * @return ArchRentalBooking
     */
    public function setCollectTime($collectTime)
    {
        $this->collect_time = $collectTime;

        return $this;
    }

    /**
     * Get collectTime
     *
     * @return string
     */
    public function getCollectTime()
    {
        return $this->collect_time;
    }

    /**
     * Set returnDate
     *
     * @param \DateTime $returnDate
     *
     * @return ArchRentalBooking
     */
    public function setReturnDate($returnDate)
    {
        $this->return_date = $returnDate;

        return $this;
    }

    /**
     * Get returnDate
     *
     * @return \DateTime
     */
    public function getReturnDate()
    {
        return $this->return_date;
    }

    /**
     * Add guest
     *
     * @param \CmsBundle\Entity\ArchRentalBookingGuest $guest
     *
     * @return ArchRentalBooking
     */
    public function addGuest(\CmsBundle\Entity\ArchRentalBookingGuest $guest)
    {
        $this->guests[] = $guest;

        return $this;
    }

    /**
     * Remove guest
     *
     * @param \CmsBundle\Entity\ArchRentalBookingGuest $guest
     */
    public function removeGuest(\CmsBundle\Entity\ArchRentalBookingGuest $guest)
    {
        $this->guests->removeElement($guest);
    }

    /**
     * Get guests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuests()
    {
        return $this->guests;
    }
}
