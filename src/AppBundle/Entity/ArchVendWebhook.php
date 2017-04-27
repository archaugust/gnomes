<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_vend_webhook")
 */
class ArchVendWebhook
{
    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;
    
    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return ArchVendWebhook
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
     * Set type
     *
     * @param string $type
     *
     * @return ArchVendWebhook
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return ArchVendWebhook
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
