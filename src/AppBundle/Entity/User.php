<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="datetime")
     */
    private $updated_at;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $deleted_at;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $purchase_total;
    
    /** 
     * @ORM\Column(type="string", length=100)
     */
    private $customer_code;
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $company_name;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $first_name;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $physical_address1;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $physical_address2;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $physical_suburb;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $physical_city;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $physical_postcode;
    

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $physical_state;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $physical_country_ID;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $postal_address1;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $postal_address2;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $postal_suburb;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $postal_city;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $postal_postcode;
    
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $postal_state;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $postal_country_ID;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set purchaseTotal
     *
     * @param string $purchaseTotal
     *
     * @return User
     */
    public function setPurchaseTotal($purchaseTotal)
    {
        $this->purchase_total = $purchaseTotal;

        return $this;
    }

    /**
     * Get purchaseTotal
     *
     * @return string
     */
    public function getPurchaseTotal()
    {
        return $this->purchase_total;
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
     * Set physicalCountryID
     *
     * @param string $physicalCountryID
     *
     * @return User
     */
    public function setPhysicalCountryID($physicalCountryID)
    {
        $this->physical_country_ID = $physicalCountryID;

        return $this;
    }

    /**
     * Get physicalCountryID
     *
     * @return string
     */
    public function getPhysicalCountryID()
    {
        return $this->physical_country_ID;
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
     * Set postalCountryID
     *
     * @param string $postalCountryID
     *
     * @return User
     */
    public function setPostalCountryID($postalCountryID)
    {
        $this->postal_country_ID = $postalCountryID;

        return $this;
    }

    /**
     * Get postalCountryID
     *
     * @return string
     */
    public function getPostalCountryID()
    {
        return $this->postal_country_ID;
    }
}
