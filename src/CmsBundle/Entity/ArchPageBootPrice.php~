<?php
namespace CmsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_boot_price")
 */
class ArchPageBootPrice
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
    private $page_id;

    /**
     * @ORM\ManyToOne(targetEntity="ArchPageBoot", inversedBy="prices")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $colour;

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
     * Set pageId
     *
     * @param integer $pageId
     *
     * @return ArchPageBootPrice
     */
    public function setPageId($pageId)
    {
        $this->page_id = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArchPageBootPrice
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
     * Set price
     *
     * @param string $price
     *
     * @return ArchPageBootPrice
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set colour
     *
     * @param string $colour
     *
     * @return ArchPageBootPrice
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * Get colour
     *
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * Set page
     *
     * @param \CmsBundle\Entity\ArchPageBoot $page
     *
     * @return ArchPageBootPrice
     */
    public function setPage(\CmsBundle\Entity\ArchPageBoot $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \CmsBundle\Entity\ArchPageBoot
     */
    public function getPage()
    {
        return $this->page;
    }
}
