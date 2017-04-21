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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $has_variants;
    
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
     * @ORM\Column(type="decimal", precision=10, scale=5)
     */
    private $supply_price;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=5)
     */
    private $retail_price;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=5)
     */
    private $price;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=5, nullable=true)
     */
    private $discount_price;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=5)
     */
    private $tax;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tax_id;
    
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
    private $image;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $video;
    
    /**
     * @ORM\OneToMany(targetEntity="ArchProductImage", mappedBy="product")
     */
    private $images;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $views;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $adds;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_description;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $outlet_id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $count;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $reorder_point;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $restock_level;
    
    public function __construct() {
    	$this->images = new ArrayCollection();
    	$this->price = $this->supply_price = $this->retail_price = $this->views = $this->count = $this->reorder_point = $this->restock_level = $this->adds = 0;
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
     * Set image
     *
     * @param string $image
     *
     * @return ArchProduct
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return ArchProduct
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return ArchProduct
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set adds
     *
     * @param integer $adds
     *
     * @return ArchProduct
     */
    public function setAdds($adds)
    {
        $this->adds = $adds;

        return $this;
    }

    /**
     * Get adds
     *
     * @return integer
     */
    public function getAdds()
    {
        return $this->adds;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return ArchProduct
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
     * @return ArchProduct
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
     * Set outletId
     *
     * @param string $outletId
     *
     * @return ArchProduct
     */
    public function setOutletId($outletId)
    {
        $this->outlet_id = $outletId;

        return $this;
    }

    /**
     * Get outletId
     *
     * @return string
     */
    public function getOutletId()
    {
        return $this->outlet_id;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return ArchProduct
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set reorderPoint
     *
     * @param integer $reorderPoint
     *
     * @return ArchProduct
     */
    public function setReorderPoint($reorderPoint)
    {
        $this->reorder_point = $reorderPoint;

        return $this;
    }

    /**
     * Get reorderPoint
     *
     * @return integer
     */
    public function getReorderPoint()
    {
        return $this->reorder_point;
    }

    /**
     * Set restockLevel
     *
     * @param integer $restockLevel
     *
     * @return ArchProduct
     */
    public function setRestockLevel($restockLevel)
    {
        $this->restock_level = $restockLevel;

        return $this;
    }

    /**
     * Get restockLevel
     *
     * @return integer
     */
    public function getRestockLevel()
    {
        return $this->restock_level;
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
     * Set price
     *
     * @param string $price
     *
     * @return ArchProduct
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
}
