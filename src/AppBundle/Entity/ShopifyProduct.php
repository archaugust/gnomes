<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_shopify_product")
 */
class ShopifyProduct
{
    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    private $id;
    
    /**
     * @ORM\Column(type="text")
     */
    private $name;
        
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $handle;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return ShopifyProduct
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ShopifyProduct
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
     * @return ShopifyProduct
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
}
