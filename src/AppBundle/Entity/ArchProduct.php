<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gno1_arch_product")
 */
class ArchProduct
{
    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $variant_parent_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchProduct", inversedBy="variants")
     * @ORM\JoinColumn(name="variant_parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $has_variants;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchProduct", mappedBy="parent")
     */
    private $variants;
    
    /**
     * @ORM\Column(type="text")
     */
    private $name;
        
    /**
     * @ORM\Column(type="text")
     */
    private $base_name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    
    /** 
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $handle;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $product_type;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $variant_option_one_name;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $variant_option_one_value;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $variant_option_two_name;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $variant_option_two_value;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $variant_option_three_name;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $variant_option_three_value;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tags;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $sku;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=5, nullable=true)
     */
    private $supply_price;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=5, nullable=true)
     */
    private $retail_price;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=5, nullable=true)
     */
    private $discount_price;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=5, nullable=true)
     */
    private $tax;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tax_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArchTax")
     * @ORM\JoinColumn(name="tax_id", referencedColumnName="id")
     */
    private $tax_map;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $brand_name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $supplier_name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $collections;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchProductInventory", mappedBy="product")
     */
    private $inventory;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchProductImage", mappedBy="product")
     */
    private $images;

    public function __construct() {
    	$this->inventory = new ArrayCollection();
    	$this->images = new ArrayCollection();
    	$this->variants = new ArrayCollection();
    }


    /**
     * Set id
     *
     * @param string $id
     *
     * @return ArchProduct
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
     * Set variantParentId
     *
     * @param string $variantParentId
     *
     * @return ArchProduct
     */
    public function setVariantParentId($variantParentId)
    {
        $this->variant_parent_id = $variantParentId;

        return $this;
    }

    /**
     * Get variantParentId
     *
     * @return string
     */
    public function getVariantParentId()
    {
        return $this->variant_parent_id;
    }

    /**
     * Set hasVariants
     *
     * @param boolean $hasVariants
     *
     * @return ArchProduct
     */
    public function setHasVariants($hasVariants)
    {
        $this->has_variants = $hasVariants;

        return $this;
    }

    /**
     * Get hasVariants
     *
     * @return boolean
     */
    public function getHasVariants()
    {
        return $this->has_variants;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArchProduct
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
     * Set description
     *
     * @param string $description
     *
     * @return ArchProduct
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ArchProduct
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
     * Set handle
     *
     * @param string $handle
     *
     * @return ArchProduct
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
     * Set productType
     *
     * @param string $productType
     *
     * @return ArchProduct
     */
    public function setProductType($productType)
    {
        $this->product_type = $productType;

        return $this;
    }

    /**
     * Get productType
     *
     * @return string
     */
    public function getProductType()
    {
        return $this->product_type;
    }

    /**
     * Set variantOptionOneName
     *
     * @param string $variantOptionOneName
     *
     * @return ArchProduct
     */
    public function setVariantOptionOneName($variantOptionOneName)
    {
        $this->variant_option_one_name = $variantOptionOneName;

        return $this;
    }

    /**
     * Get variantOptionOneName
     *
     * @return string
     */
    public function getVariantOptionOneName()
    {
        return $this->variant_option_one_name;
    }

    /**
     * Set variantOptionOneValue
     *
     * @param string $variantOptionOneValue
     *
     * @return ArchProduct
     */
    public function setVariantOptionOneValue($variantOptionOneValue)
    {
        $this->variant_option_one_value = $variantOptionOneValue;

        return $this;
    }

    /**
     * Get variantOptionOneValue
     *
     * @return string
     */
    public function getVariantOptionOneValue()
    {
        return $this->variant_option_one_value;
    }

    /**
     * Set variantOptionTwoName
     *
     * @param string $variantOptionTwoName
     *
     * @return ArchProduct
     */
    public function setVariantOptionTwoName($variantOptionTwoName)
    {
        $this->variant_option_two_name = $variantOptionTwoName;

        return $this;
    }

    /**
     * Get variantOptionTwoName
     *
     * @return string
     */
    public function getVariantOptionTwoName()
    {
        return $this->variant_option_two_name;
    }

    /**
     * Set variantOptionTwoValue
     *
     * @param string $variantOptionTwoValue
     *
     * @return ArchProduct
     */
    public function setVariantOptionTwoValue($variantOptionTwoValue)
    {
        $this->variant_option_two_value = $variantOptionTwoValue;

        return $this;
    }

    /**
     * Get variantOptionTwoValue
     *
     * @return string
     */
    public function getVariantOptionTwoValue()
    {
        return $this->variant_option_two_value;
    }

    /**
     * Set variantOptionThreeName
     *
     * @param string $variantOptionThreeName
     *
     * @return ArchProduct
     */
    public function setVariantOptionThreeName($variantOptionThreeName)
    {
        $this->variant_option_three_name = $variantOptionThreeName;

        return $this;
    }

    /**
     * Get variantOptionThreeName
     *
     * @return string
     */
    public function getVariantOptionThreeName()
    {
        return $this->variant_option_three_name;
    }

    /**
     * Set variantOptionThreeValue
     *
     * @param string $variantOptionThreeValue
     *
     * @return ArchProduct
     */
    public function setVariantOptionThreeValue($variantOptionThreeValue)
    {
        $this->variant_option_three_value = $variantOptionThreeValue;

        return $this;
    }

    /**
     * Get variantOptionThreeValue
     *
     * @return string
     */
    public function getVariantOptionThreeValue()
    {
        return $this->variant_option_three_value;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return ArchProduct
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return ArchProduct
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set supplyPrice
     *
     * @param string $supplyPrice
     *
     * @return ArchProduct
     */
    public function setSupplyPrice($supplyPrice)
    {
        $this->supply_price = $supplyPrice;

        return $this;
    }

    /**
     * Get supplyPrice
     *
     * @return string
     */
    public function getSupplyPrice()
    {
        return $this->supply_price;
    }

    /**
     * Set retailPrice
     *
     * @param string $retailPrice
     *
     * @return ArchProduct
     */
    public function setRetailPrice($retailPrice)
    {
        $this->retail_price = $retailPrice;

        return $this;
    }

    /**
     * Get retailPrice
     *
     * @return string
     */
    public function getRetailPrice()
    {
        return $this->retail_price;
    }

    /**
     * Set discountPrice
     *
     * @param string $discountPrice
     *
     * @return ArchProduct
     */
    public function setDiscountPrice($discountPrice)
    {
        $this->discount_price = $discountPrice;

        return $this;
    }

    /**
     * Get discountPrice
     *
     * @return string
     */
    public function getDiscountPrice()
    {
        return $this->discount_price;
    }

    /**
     * Set tax
     *
     * @param string $tax
     *
     * @return ArchProduct
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return string
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set taxId
     *
     * @param string $taxId
     *
     * @return ArchProduct
     */
    public function setTaxId($taxId)
    {
        $this->tax_id = $taxId;

        return $this;
    }

    /**
     * Get taxId
     *
     * @return string
     */
    public function getTaxId()
    {
        return $this->tax_id;
    }

    /**
     * Set brandName
     *
     * @param string $brandName
     *
     * @return ArchProduct
     */
    public function setBrandName($brandName)
    {
        $this->brand_name = $brandName;

        return $this;
    }

    /**
     * Get brandName
     *
     * @return string
     */
    public function getBrandName()
    {
        return $this->brand_name;
    }

    /**
     * Set supplierName
     *
     * @param string $supplierName
     *
     * @return ArchProduct
     */
    public function setSupplierName($supplierName)
    {
        $this->supplier_name = $supplierName;

        return $this;
    }

    /**
     * Get supplierName
     *
     * @return string
     */
    public function getSupplierName()
    {
        return $this->supplier_name;
    }

    /**
     * Set collections
     *
     * @param string $collections
     *
     * @return ArchProduct
     */
    public function setCollections($collections)
    {
        $this->collections = $collections;

        return $this;
    }

    /**
     * Get collections
     *
     * @return string
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\ArchProduct $parent
     *
     * @return ArchProduct
     */
    public function setParent(\AppBundle\Entity\ArchProduct $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\ArchProduct
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add variant
     *
     * @param \AppBundle\Entity\ArchProduct $variant
     *
     * @return ArchProduct
     */
    public function addVariant(\AppBundle\Entity\ArchProduct $variant)
    {
        $this->variants[] = $variant;

        return $this;
    }

    /**
     * Remove variant
     *
     * @param \AppBundle\Entity\ArchProduct $variant
     */
    public function removeVariant(\AppBundle\Entity\ArchProduct $variant)
    {
        $this->variants->removeElement($variant);
    }

    /**
     * Get variants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * Set taxMap
     *
     * @param \AppBundle\Entity\ArchTax $taxMap
     *
     * @return ArchProduct
     */
    public function setTaxMap(\AppBundle\Entity\ArchTax $taxMap = null)
    {
        $this->tax_map = $taxMap;

        return $this;
    }

    /**
     * Get taxMap
     *
     * @return \AppBundle\Entity\ArchTax
     */
    public function getTaxMap()
    {
        return $this->tax_map;
    }

    /**
     * Add inventory
     *
     * @param \AppBundle\Entity\ArchProductInventory $inventory
     *
     * @return ArchProduct
     */
    public function addInventory(\AppBundle\Entity\ArchProductInventory $inventory)
    {
        $this->inventory[] = $inventory;

        return $this;
    }

    /**
     * Remove inventory
     *
     * @param \AppBundle\Entity\ArchProductInventory $inventory
     */
    public function removeInventory(\AppBundle\Entity\ArchProductInventory $inventory)
    {
        $this->inventory->removeElement($inventory);
    }

    /**
     * Get inventory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\ArchProductImage $image
     *
     * @return ArchProduct
     */
    public function addImage(\AppBundle\Entity\ArchProductImage $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\ArchProductImage $image
     */
    public function removeImage(\AppBundle\Entity\ArchProductImage $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set baseName
     *
     * @param string $baseName
     *
     * @return ArchProduct
     */
    public function setBaseName($baseName)
    {
        $this->base_name = $baseName;

        return $this;
    }

    /**
     * Get baseName
     *
     * @return string
     */
    public function getBaseName()
    {
        return $this->base_name;
    }
}
