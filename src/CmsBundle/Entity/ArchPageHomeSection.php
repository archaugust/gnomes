<?php
namespace CmsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_home_section")
 */
class ArchPageHomeSection
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
     * @ORM\ManyToOne(targetEntity="ArchPageHome", inversedBy="sections")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $filename;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $banner_text;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $link_text;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $link_url;

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
     * @return ArchPageHomeSection
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
     * Set filename
     *
     * @param string $filename
     *
     * @return ArchPageHomeSection
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set bannerText
     *
     * @param string $bannerText
     *
     * @return ArchPageHomeSection
     */
    public function setBannerText($bannerText)
    {
        $this->banner_text = $bannerText;

        return $this;
    }

    /**
     * Get bannerText
     *
     * @return string
     */
    public function getBannerText()
    {
        return $this->banner_text;
    }

    /**
     * Set linkText
     *
     * @param string $linkText
     *
     * @return ArchPageHomeSection
     */
    public function setLinkText($linkText)
    {
        $this->link_text = $linkText;

        return $this;
    }

    /**
     * Get linkText
     *
     * @return string
     */
    public function getLinkText()
    {
        return $this->link_text;
    }

    /**
     * Set linkUrl
     *
     * @param string $linkUrl
     *
     * @return ArchPageHomeSection
     */
    public function setLinkUrl($linkUrl)
    {
        $this->link_url = $linkUrl;

        return $this;
    }

    /**
     * Get linkUrl
     *
     * @return string
     */
    public function getLinkUrl()
    {
        return $this->link_url;
    }

    /**
     * Set page
     *
     * @param \CmsBundle\Entity\ArchPageHome $page
     *
     * @return ArchPageHomeSection
     */
    public function setPage(\CmsBundle\Entity\ArchPageHome $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \CmsBundle\Entity\ArchPageHome
     */
    public function getPage()
    {
        return $this->page;
    }
}
