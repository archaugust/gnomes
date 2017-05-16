<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_ski_finder_request")
 */
class ArchSkiFinderRequest
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $height;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $weight;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $age;
    
    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $technical_ability;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $experience;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $approach;
    
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $favourite_hangouts;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true)
     */
    private $favourite_flavour;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $specialist;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fat_or_skinny;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $retired_flotilla;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $likes_dislikes;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $expectations;


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
     * @return ArchSkiFinderRequest
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
     * Set name
     *
     * @param string $name
     *
     * @return ArchSkiFinderRequest
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
     * Set email
     *
     * @param string $email
     *
     * @return ArchSkiFinderRequest
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
     * Set height
     *
     * @param string $height
     *
     * @return ArchSkiFinderRequest
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
     * @return ArchSkiFinderRequest
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
     * Set age
     *
     * @param string $age
     *
     * @return ArchSkiFinderRequest
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return string
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return ArchSkiFinderRequest
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set technicalAbility
     *
     * @param integer $technicalAbility
     *
     * @return ArchSkiFinderRequest
     */
    public function setTechnicalAbility($technicalAbility)
    {
        $this->technical_ability = $technicalAbility;

        return $this;
    }

    /**
     * Get technicalAbility
     *
     * @return integer
     */
    public function getTechnicalAbility()
    {
        return $this->technical_ability;
    }

    /**
     * Set experience
     *
     * @param integer $experience
     *
     * @return ArchSkiFinderRequest
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return integer
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set approach
     *
     * @param integer $approach
     *
     * @return ArchSkiFinderRequest
     */
    public function setApproach($approach)
    {
        $this->approach = $approach;

        return $this;
    }

    /**
     * Get approach
     *
     * @return integer
     */
    public function getApproach()
    {
        return $this->approach;
    }

    /**
     * Set favouriteHangouts
     *
     * @param string $favouriteHangouts
     *
     * @return ArchSkiFinderRequest
     */
    public function setFavouriteHangouts($favouriteHangouts)
    {
        $this->favourite_hangouts = $favouriteHangouts;

        return $this;
    }

    /**
     * Get favouriteHangouts
     *
     * @return string
     */
    public function getFavouriteHangouts()
    {
        return $this->favourite_hangouts;
    }

    /**
     * Set favouriteFlavour
     *
     * @param integer $favouriteFlavour
     *
     * @return ArchSkiFinderRequest
     */
    public function setFavouriteFlavour($favouriteFlavour)
    {
        $this->favourite_flavour = $favouriteFlavour;

        return $this;
    }

    /**
     * Get favouriteFlavour
     *
     * @return integer
     */
    public function getFavouriteFlavour()
    {
        return $this->favourite_flavour;
    }

    /**
     * Set specialist
     *
     * @param integer $specialist
     *
     * @return ArchSkiFinderRequest
     */
    public function setSpecialist($specialist)
    {
        $this->specialist = $specialist;

        return $this;
    }

    /**
     * Get specialist
     *
     * @return integer
     */
    public function getSpecialist()
    {
        return $this->specialist;
    }

    /**
     * Set fatOrSkinny
     *
     * @param integer $fatOrSkinny
     *
     * @return ArchSkiFinderRequest
     */
    public function setFatOrSkinny($fatOrSkinny)
    {
        $this->fat_or_skinny = $fatOrSkinny;

        return $this;
    }

    /**
     * Get fatOrSkinny
     *
     * @return integer
     */
    public function getFatOrSkinny()
    {
        return $this->fat_or_skinny;
    }

    /**
     * Set retiredFlotilla
     *
     * @param string $retiredFlotilla
     *
     * @return ArchSkiFinderRequest
     */
    public function setRetiredFlotilla($retiredFlotilla)
    {
        $this->retired_flotilla = $retiredFlotilla;

        return $this;
    }

    /**
     * Get retiredFlotilla
     *
     * @return string
     */
    public function getRetiredFlotilla()
    {
        return $this->retired_flotilla;
    }

    /**
     * Set likesDislikes
     *
     * @param string $likesDislikes
     *
     * @return ArchSkiFinderRequest
     */
    public function setLikesDislikes($likesDislikes)
    {
        $this->likes_dislikes = $likesDislikes;

        return $this;
    }

    /**
     * Get likesDislikes
     *
     * @return string
     */
    public function getLikesDislikes()
    {
        return $this->likes_dislikes;
    }

    /**
     * Set expectations
     *
     * @param string $expectations
     *
     * @return ArchSkiFinderRequest
     */
    public function setExpectations($expectations)
    {
        $this->expectations = $expectations;

        return $this;
    }

    /**
     * Get expectations
     *
     * @return string
     */
    public function getExpectations()
    {
        return $this->expectations;
    }
}
