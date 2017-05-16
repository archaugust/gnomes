<?php
namespace CmsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_rental_section")
 */
class ArchPageRentalSection
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
     * @ORM\ManyToOne(targetEntity="ArchPageRental", inversedBy="sections")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $standard_image;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $standard_content;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $standard_price;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $performance_image;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $performance_content;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $performance_price;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $demo_image;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $demo_content;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $demo_price;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $touring_image;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $touring_content;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $touring_price;

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
     * @return ArchPageRentalSection
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
     * @return ArchPageRentalSection
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
     * Set standardImage
     *
     * @param string $standardImage
     *
     * @return ArchPageRentalSection
     */
    public function setStandardImage($standardImage)
    {
        $this->standard_image = $standardImage;

        return $this;
    }

    /**
     * Get standardImage
     *
     * @return string
     */
    public function getStandardImage()
    {
        return $this->standard_image;
    }

    /**
     * Set standardContent
     *
     * @param string $standardContent
     *
     * @return ArchPageRentalSection
     */
    public function setStandardContent($standardContent)
    {
        $this->standard_content = $standardContent;

        return $this;
    }

    /**
     * Get standardContent
     *
     * @return string
     */
    public function getStandardContent()
    {
        return $this->standard_content;
    }

    /**
     * Set standardPrice
     *
     * @param string $standardPrice
     *
     * @return ArchPageRentalSection
     */
    public function setStandardPrice($standardPrice)
    {
        $this->standard_price = $standardPrice;

        return $this;
    }

    /**
     * Get standardPrice
     *
     * @return string
     */
    public function getStandardPrice()
    {
        return $this->standard_price;
    }

    /**
     * Set performanceImage
     *
     * @param string $performanceImage
     *
     * @return ArchPageRentalSection
     */
    public function setPerformanceImage($performanceImage)
    {
        $this->performance_image = $performanceImage;

        return $this;
    }

    /**
     * Get performanceImage
     *
     * @return string
     */
    public function getPerformanceImage()
    {
        return $this->performance_image;
    }

    /**
     * Set performanceContent
     *
     * @param string $performanceContent
     *
     * @return ArchPageRentalSection
     */
    public function setPerformanceContent($performanceContent)
    {
        $this->performance_content = $performanceContent;

        return $this;
    }

    /**
     * Get performanceContent
     *
     * @return string
     */
    public function getPerformanceContent()
    {
        return $this->performance_content;
    }

    /**
     * Set performancePrice
     *
     * @param string $performancePrice
     *
     * @return ArchPageRentalSection
     */
    public function setPerformancePrice($performancePrice)
    {
        $this->performance_price = $performancePrice;

        return $this;
    }

    /**
     * Get performancePrice
     *
     * @return string
     */
    public function getPerformancePrice()
    {
        return $this->performance_price;
    }

    /**
     * Set demoImage
     *
     * @param string $demoImage
     *
     * @return ArchPageRentalSection
     */
    public function setDemoImage($demoImage)
    {
        $this->demo_image = $demoImage;

        return $this;
    }

    /**
     * Get demoImage
     *
     * @return string
     */
    public function getDemoImage()
    {
        return $this->demo_image;
    }

    /**
     * Set demoContent
     *
     * @param string $demoContent
     *
     * @return ArchPageRentalSection
     */
    public function setDemoContent($demoContent)
    {
        $this->demo_content = $demoContent;

        return $this;
    }

    /**
     * Get demoContent
     *
     * @return string
     */
    public function getDemoContent()
    {
        return $this->demo_content;
    }

    /**
     * Set demoPrice
     *
     * @param string $demoPrice
     *
     * @return ArchPageRentalSection
     */
    public function setDemoPrice($demoPrice)
    {
        $this->demo_price = $demoPrice;

        return $this;
    }

    /**
     * Get demoPrice
     *
     * @return string
     */
    public function getDemoPrice()
    {
        return $this->demo_price;
    }

    /**
     * Set touringImage
     *
     * @param string $touringImage
     *
     * @return ArchPageRentalSection
     */
    public function setTouringImage($touringImage)
    {
        $this->touring_image = $touringImage;

        return $this;
    }

    /**
     * Get touringImage
     *
     * @return string
     */
    public function getTouringImage()
    {
        return $this->touring_image;
    }

    /**
     * Set touringContent
     *
     * @param string $touringContent
     *
     * @return ArchPageRentalSection
     */
    public function setTouringContent($touringContent)
    {
        $this->touring_content = $touringContent;

        return $this;
    }

    /**
     * Get touringContent
     *
     * @return string
     */
    public function getTouringContent()
    {
        return $this->touring_content;
    }

    /**
     * Set touringPrice
     *
     * @param string $touringPrice
     *
     * @return ArchPageRentalSection
     */
    public function setTouringPrice($touringPrice)
    {
        $this->touring_price = $touringPrice;

        return $this;
    }

    /**
     * Get touringPrice
     *
     * @return string
     */
    public function getTouringPrice()
    {
        return $this->touring_price;
    }

    /**
     * Set page
     *
     * @param \CmsBundle\Entity\ArchPageRental $page
     *
     * @return ArchPageRentalSection
     */
    public function setPage(\CmsBundle\Entity\ArchPageRental $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \CmsBundle\Entity\ArchPageRental
     */
    public function getPage()
    {
        return $this->page;
    }
}
