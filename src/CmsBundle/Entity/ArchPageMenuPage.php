<?php
namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_page_menu_page")
 */
class ArchPageMenuPage
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
    private $menu_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchPageMenu", inversedBy="pages")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     */
    private $menu;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $handle;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $image_main;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png","image/gif" })
     */
    private $image_middle;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_top;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_bottom;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_description;

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
     * Set menuId
     *
     * @param integer $menuId
     *
     * @return ArchPageMenuPage
     */
    public function setMenuId($menuId)
    {
        $this->menu_id = $menuId;

        return $this;
    }

    /**
     * Get menuId
     *
     * @return integer
     */
    public function getMenuId()
    {
        return $this->menu_id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArchPageMenuPage
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
     * Set handle
     *
     * @param string $handle
     *
     * @return ArchPageMenuPage
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * Get handle
     *
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * Set imageMain
     *
     * @param string $imageMain
     *
     * @return ArchPageMenuPage
     */
    public function setImageMain($imageMain)
    {
        $this->image_main = $imageMain;

        return $this;
    }

    /**
     * Get imageMain
     *
     * @return string
     */
    public function getImageMain()
    {
        return $this->image_main;
    }

    /**
     * Set imageMiddle
     *
     * @param string $imageMiddle
     *
     * @return ArchPageMenuPage
     */
    public function setImageMiddle($imageMiddle)
    {
        $this->image_middle = $imageMiddle;

        return $this;
    }

    /**
     * Get imageMiddle
     *
     * @return string
     */
    public function getImageMiddle()
    {
        return $this->image_middle;
    }

    /**
     * Set contentTop
     *
     * @param string $contentTop
     *
     * @return ArchPageMenuPage
     */
    public function setContentTop($contentTop)
    {
        $this->content_top = $contentTop;

        return $this;
    }

    /**
     * Get contentTop
     *
     * @return string
     */
    public function getContentTop()
    {
        return $this->content_top;
    }

    /**
     * Set contentBottom
     *
     * @param string $contentBottom
     *
     * @return ArchPageMenuPage
     */
    public function setContentBottom($contentBottom)
    {
        $this->content_bottom = $contentBottom;

        return $this;
    }

    /**
     * Get contentBottom
     *
     * @return string
     */
    public function getContentBottom()
    {
        return $this->content_bottom;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ArchPageMenuPage
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return ArchPageMenuPage
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return ArchPageMenuPage
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set menu
     *
     * @param \CmsBundle\Entity\ArchPageMenu $menu
     *
     * @return ArchPageMenuPage
     */
    public function setMenu(\CmsBundle\Entity\ArchPageMenu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \CmsBundle\Entity\ArchPageMenu
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
