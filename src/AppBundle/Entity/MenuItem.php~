<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_menu_item")
 */
class MenuItem
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
     * @ORM\Column(type="integer")
     */
    private $parent_id;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $alias;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $page_type;
    
    /**
     * @ORM\Column(type="text")
     */
    private $page_type_id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $sort_order;

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
     * @return MenuItem
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return MenuItem
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return MenuItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return MenuItem
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set pageType
     *
     * @param string $pageType
     *
     * @return MenuItem
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

    /**
     * Set pageTypeId
     *
     * @param string $pageTypeId
     *
     * @return MenuItem
     */
    public function setPageTypeId($pageTypeId)
    {
        $this->page_type_id = $pageTypeId;

        return $this;
    }

    /**
     * Get pageTypeId
     *
     * @return string
     */
    public function getPageTypeId()
    {
        return $this->page_type_id;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return MenuItem
     */
    public function setSortOrder($sortOrder)
    {
        $this->sort_order = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sort_order;
    }
}
