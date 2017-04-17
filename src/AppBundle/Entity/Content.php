<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_content")
 */
class Content
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $alias;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $content;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $core;
    
    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $meta_title;
    
    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $meta_description;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $hits;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $date_added;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $date_modified;

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
     * Set title
     *
     * @param string $title
     *
     * @return Content
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
     * @return Content
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
     * Set content
     *
     * @param string $content
     *
     * @return Content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set core
     *
     * @param boolean $core
     *
     * @return Content
     */
    public function setCore($core)
    {
        $this->core = $core;

        return $this;
    }

    /**
     * Get core
     *
     * @return boolean
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Content
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
     * @return Content
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
     * Set hits
     *
     * @param integer $hits
     *
     * @return Content
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Content
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     *
     * @return Content
     */
    public function setDateModified($dateModified)
    {
        $this->date_modified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
}
