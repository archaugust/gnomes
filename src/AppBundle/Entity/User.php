<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $customer_id;
    
    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $account_type;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $balance;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $total_spent;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $year_to_date;
    
    /** 
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $customer_code;
    
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $company_name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $first_name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $last_name;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $full_name;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $physical_address1;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $physical_address2;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $physical_suburb;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $physical_city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $physical_postcode;
    

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $physical_state;
    
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $physical_country_id;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $postal_address1;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $postal_address2;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $postal_suburb;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $postal_city;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $postal_postcode;
        
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $postal_state;
    
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $postal_country_id;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_of_birth;
    
    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sex;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepts_marketing;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $order_count;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchOrder", mappedBy="customer")
     */
    private $orders;
    
    public function __construct()
    {
        parent::__construct();
        $this->order_count = 0;
        $this->accepts_marketing = 1;
        $this->orders = new ArrayCollection();
    }


    /**
     * Set customerId
     *
     * @param string $customerId
     *
     * @return User
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Set accountType
     *
     * @param string $accountType
     *
     * @return User
     */
    public function setAccountType($accountType)
    {
        $this->account_type = $accountType;

        return $this;
    }

    /**
     * Get accountType
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->account_type;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * Set balance
     *
     * @param string $balance
     *
     * @return User
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set totalSpent
     *
     * @param string $totalSpent
     *
     * @return User
     */
    public function setTotalSpent($totalSpent)
    {
        $this->total_spent = $totalSpent;

        return $this;
    }

    /**
     * Get totalSpent
     *
     * @return string
     */
    public function getTotalSpent()
    {
        return $this->total_spent;
    }

    /**
     * Set yearToDate
     *
     * @param string $yearToDate
     *
     * @return User
     */
    public function setYearToDate($yearToDate)
    {
        $this->year_to_date = $yearToDate;

        return $this;
    }

    /**
     * Get yearToDate
     *
     * @return string
     */
    public function getYearToDate()
    {
        return $this->year_to_date;
    }

    /**
     * Set customerCode
     *
     * @param string $customerCode
     *
     * @return User
     */
    public function setCustomerCode($customerCode)
    {
        $this->customer_code = $customerCode;

        return $this;
    }

    /**
     * Get customerCode
     *
     * @return string
     */
    public function getCustomerCode()
    {
        return $this->customer_code;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return User
     */
    public function setCompanyName($companyName)
    {
        $this->company_name = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
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
     * @return User
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
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
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
     * Set mobile
     *
     * @param string $mobile
     *
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return User
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return User
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set physicalAddress1
     *
     * @param string $physicalAddress1
     *
     * @return User
     */
    public function setPhysicalAddress1($physicalAddress1)
    {
        $this->physical_address1 = $physicalAddress1;

        return $this;
    }

    /**
     * Get physicalAddress1
     *
     * @return string
     */
    public function getPhysicalAddress1()
    {
        return $this->physical_address1;
    }

    /**
     * Set physicalAddress2
     *
     * @param string $physicalAddress2
     *
     * @return User
     */
    public function setPhysicalAddress2($physicalAddress2)
    {
        $this->physical_address2 = $physicalAddress2;

        return $this;
    }

    /**
     * Get physicalAddress2
     *
     * @return string
     */
    public function getPhysicalAddress2()
    {
        return $this->physical_address2;
    }

    /**
     * Set physicalSuburb
     *
     * @param string $physicalSuburb
     *
     * @return User
     */
    public function setPhysicalSuburb($physicalSuburb)
    {
        $this->physical_suburb = $physicalSuburb;

        return $this;
    }

    /**
     * Get physicalSuburb
     *
     * @return string
     */
    public function getPhysicalSuburb()
    {
        return $this->physical_suburb;
    }

    /**
     * Set physicalCity
     *
     * @param string $physicalCity
     *
     * @return User
     */
    public function setPhysicalCity($physicalCity)
    {
        $this->physical_city = $physicalCity;

        return $this;
    }

    /**
     * Get physicalCity
     *
     * @return string
     */
    public function getPhysicalCity()
    {
        return $this->physical_city;
    }

    /**
     * Set physicalPostcode
     *
     * @param string $physicalPostcode
     *
     * @return User
     */
    public function setPhysicalPostcode($physicalPostcode)
    {
        $this->physical_postcode = $physicalPostcode;

        return $this;
    }

    /**
     * Get physicalPostcode
     *
     * @return string
     */
    public function getPhysicalPostcode()
    {
        return $this->physical_postcode;
    }

    /**
     * Set physicalState
     *
     * @param string $physicalState
     *
     * @return User
     */
    public function setPhysicalState($physicalState)
    {
        $this->physical_state = $physicalState;

        return $this;
    }

    /**
     * Get physicalState
     *
     * @return string
     */
    public function getPhysicalState()
    {
        return $this->physical_state;
    }

    /**
     * Set physicalCountryId
     *
     * @param string $physicalCountryId
     *
     * @return User
     */
    public function setPhysicalCountryId($physicalCountryId)
    {
        $this->physical_country_id = $physicalCountryId;

        return $this;
    }

    /**
     * Get physicalCountryId
     *
     * @return string
     */
    public function getPhysicalCountryId()
    {
        return $this->physical_country_id;
    }

    /**
     * Set postalAddress1
     *
     * @param string $postalAddress1
     *
     * @return User
     */
    public function setPostalAddress1($postalAddress1)
    {
        $this->postal_address1 = $postalAddress1;

        return $this;
    }

    /**
     * Get postalAddress1
     *
     * @return string
     */
    public function getPostalAddress1()
    {
        return $this->postal_address1;
    }

    /**
     * Set postalAddress2
     *
     * @param string $postalAddress2
     *
     * @return User
     */
    public function setPostalAddress2($postalAddress2)
    {
        $this->postal_address2 = $postalAddress2;

        return $this;
    }

    /**
     * Get postalAddress2
     *
     * @return string
     */
    public function getPostalAddress2()
    {
        return $this->postal_address2;
    }

    /**
     * Set postalSuburb
     *
     * @param string $postalSuburb
     *
     * @return User
     */
    public function setPostalSuburb($postalSuburb)
    {
        $this->postal_suburb = $postalSuburb;

        return $this;
    }

    /**
     * Get postalSuburb
     *
     * @return string
     */
    public function getPostalSuburb()
    {
        return $this->postal_suburb;
    }

    /**
     * Set postalCity
     *
     * @param string $postalCity
     *
     * @return User
     */
    public function setPostalCity($postalCity)
    {
        $this->postal_city = $postalCity;

        return $this;
    }

    /**
     * Get postalCity
     *
     * @return string
     */
    public function getPostalCity()
    {
        return $this->postal_city;
    }

    /**
     * Set postalPostcode
     *
     * @param string $postalPostcode
     *
     * @return User
     */
    public function setPostalPostcode($postalPostcode)
    {
        $this->postal_postcode = $postalPostcode;

        return $this;
    }

    /**
     * Get postalPostcode
     *
     * @return string
     */
    public function getPostalPostcode()
    {
        return $this->postal_postcode;
    }

    /**
     * Set postalState
     *
     * @param string $postalState
     *
     * @return User
     */
    public function setPostalState($postalState)
    {
        $this->postal_state = $postalState;

        return $this;
    }

    /**
     * Get postalState
     *
     * @return string
     */
    public function getPostalState()
    {
        return $this->postal_state;
    }

    /**
     * Set postalCountryId
     *
     * @param string $postalCountryId
     *
     * @return User
     */
    public function setPostalCountryId($postalCountryId)
    {
        $this->postal_country_id = $postalCountryId;

        return $this;
    }

    /**
     * Get postalCountryId
     *
     * @return string
     */
    public function getPostalCountryId()
    {
        return $this->postal_country_id;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->date_of_birth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set acceptsMarketing
     *
     * @param boolean $acceptsMarketing
     *
     * @return User
     */
    public function setAcceptsMarketing($acceptsMarketing)
    {
        $this->accepts_marketing = $acceptsMarketing;

        return $this;
    }

    /**
     * Get acceptsMarketing
     *
     * @return boolean
     */
    public function getAcceptsMarketing()
    {
        return $this->accepts_marketing;
    }

    /**
     * Set orderCount
     *
     * @param integer $orderCount
     *
     * @return User
     */
    public function setOrderCount($orderCount)
    {
        $this->order_count = $orderCount;

        return $this;
    }

    /**
     * Get orderCount
     *
     * @return integer
     */
    public function getOrderCount()
    {
        return $this->order_count;
    }

    /**
     * Set physicalCountry
     *
     * @param \AppBundle\Entity\ArchCountry $physicalCountry
     *
     * @return User
     */
    public function setPhysicalCountry(\AppBundle\Entity\ArchCountry $physicalCountry = null)
    {
        $this->physical_country = $physicalCountry;

        return $this;
    }

    /**
     * Get physicalCountry
     *
     * @return \AppBundle\Entity\ArchCountry
     */
    public function getPhysicalCountry()
    {
        return $this->physical_country;
    }

    /**
     * Set postalCountry
     *
     * @param \AppBundle\Entity\ArchCountry $postalCountry
     *
     * @return User
     */
    public function setPostalCountry(\AppBundle\Entity\ArchCountry $postalCountry = null)
    {
        $this->postal_country = $postalCountry;

        return $this;
    }

    /**
     * Get postalCountry
     *
     * @return \AppBundle\Entity\ArchCountry
     */
    public function getPostalCountry()
    {
        return $this->postal_country;
    }

    /**
     * Add order
     *
     * @param \AppBundle\Entity\ArchOrder $order
     *
     * @return User
     */
    public function addOrder(\AppBundle\Entity\ArchOrder $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \AppBundle\Entity\ArchOrder $order
     */
    public function removeOrder(\AppBundle\Entity\ArchOrder $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
