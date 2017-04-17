<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_pagetype")
 */
class PageType
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $page_type_title;

    /**
     * @ORM\Column(type="string",length=50)
     * @Assert\NotBlank()
     */
    private $page_type;

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
     * Set pageTypeTitle
     *
     * @param string $pageTypeTitle
     *
     * @return PageType
     */
    public function setPageTypeTitle($pageTypeTitle)
    {
        $this->page_type_title = $pageTypeTitle;

        return $this;
    }

    /**
     * Get pageTypeTitle
     *
     * @return string
     */
    public function getPageTypeTitle()
    {
        return $this->page_type_title;
    }

    /**
     * Set pageType
     *
     * @param string $pageType
     *
     * @return PageType
     */
    public function setPageType($pageType)
    {
        $this->page_type = $pageType;

        return $this;
    }

    /**
     * Get pageType
     *
     * @return string
     */
    public function getPageType()
    {
        return $this->page_type;
    }
}
