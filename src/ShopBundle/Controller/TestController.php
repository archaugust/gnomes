<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ArchProduct;
use AppBundle\Entity\ArchProductBrand;
use AppBundle\Entity\ArchProductType;

class TestController extends Controller
{
	/**
	 * @Route("check-collections")
	 */
	public function check() {
		$em = $this->getDoctrine();
		$items = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findAll();
		$ctr = 0;
		foreach ($items as $item) {
			if ($item->getProduct()->getVariantParentId())
				$ctr++;
		}
		
		echo ($ctr); die;
	}
		
	/**
	 * @Route("test")
	 */
	public function test() {
		for ($i=1; $i<5;$i++) 
			echo uniqid('ID') .'<br />';
		die;
		$json = '{"id":"2453f3d1-91d7-11e3-a0f5-b8ca3a64f8f4","retailer_id":"8f41ab7b-762a-11e2-b1f5-4040782fde00","sku":"102571","handle":"verigastopgo16mmsuv","variant_parent_id":null,"variant_options":[{"id":"9a93727a-3b53-102e-b60f-4abbcbc88955","name":"Size","value":"245"}],"source":"SHOPIFY","source_id":"256093101","source_variant_id":"586458469","active":true,"name":"Veriga Stop & Go 16mm (SUV)","base_name":"Veriga Stop & Go 16mm (SUV)","description":"<p>The Veriga snow chain is a 16mm chain with two patented self-tensioning and self-centring &ldquo;STOP&amp;GO&rdquo; devices. This mean it is not necessary to stop the vehicle and tension the chain, saving time. The diamond shape made of special alloy steel, with &quot;D&quot; profile links make for safe driving on snow and ice. Colour coded parts, together with easy to follow fitting instructions make this chain quick and easy to fit. It is suitable for 4x4 vehicles,vans, utes and light commercial. They come supplied in a comfortable and elegant semi-rigid carrying case with velcro that permits you to fasten it to the car trunk.<\/p>\r\n","attributed_cost":null,"supply_price":"270.00","deleted_at":null,"button_order":"0","product_type":{"id":"3ad87081-9500-11e2-b1f5-4040782fde00","name":"Snow Chains"},"supplier":{"id":"23986029-91d7-11e3-a0f5-b8ca3a64f8f4","name":"Veriga","description":null,"source":"USER"},"brand":{"id":"23956a5f-91d7-11e3-a0f5-b8ca3a64f8f4","name":"Veriga"},"price_book_entries":[{"id":"8a94239e-4a7f-0d74-91dd-a59b9a74f38e","product_id":"2453f3d1-91d7-11e3-a0f5-b8ca3a64f8f4","max_units":null,"min_units":null,"price":"434.69565","valid_from":null,"valid_to":null,"tax":"65.20435","type":"BASE","customer_group_id":"8f590125-762a-11e2-b1f5-4040782fde00","customer_group_name":"All Customers","tax_id":"ce5596ad-a177-11e2-b2b4-bc764e10976c"}],"inventory":[{"id":"3e5c564f-ce9a-1703-ff16-d38df697228d","product_id":"2453f3d1-91d7-11e3-a0f5-b8ca3a64f8f4","outlet_id":"8f4eb30f-762a-11e2-b1f5-4040782fde00","attributed_cost":"243.00","count":0,"reorder_point":"0","restock_level":"0"}],"taxes":[{"outlet_id":"8f4eb30f-762a-11e2-b1f5-4040782fde00","tax_id":"ce5596ad-a177-11e2-b2b4-bc764e10976c"}],"categories":[]}';
		dump(json_decode($json));
		die;
	}
	
	/**
	 * @Route("test-update")
	 */
	public function testUpdate() {
		$em = $this->getDoctrine()->getManager();
		
		$json = '{"id":"7c67b614-9500-11e2-b1f5-4040782fde00","retailer_id":"8f41ab7b-762a-11e2-b1f5-4040782fde00","sku":"BOO002","handle":"BoosterExpert","variant_parent_id":"430629cb-ed16-11e3-a0f5-b8ca3a64f8f4","variant_options":[],"source":"USER","source_id":null,"source_variant_id":null,"active":true,"name":"Booster Expert","base_name":"Booster Expert","description":"","attributed_cost":null,"supply_price":"31.00","deleted_at":"2017-05-06 20:40:52","button_order":"0","product_type":{"id":"eb720225-a804-11e3-a0f5-b8ca3a64f8f4","name":"Ski Boot - Accessories"},"supplier":{"id":"7c2b75e0-9500-11e2-b1f5-4040782fde00","name":"Booster","description":null,"source":"USER"},"brand":{"id":"7c2978dc-9500-11e2-b1f5-4040782fde00","name":"Booster"},"inventory":[{"id":"7c742581-9500-11e2-b1f5-4040782fde00","product_id":"7c67b614-9500-11e2-b1f5-4040782fde00","outlet_id":"8f4eb30f-762a-11e2-b1f5-4040782fde00","attributed_cost":"31.00","count":11,"reorder_point":"0","restock_level":"0"}],"taxes":[{"outlet_id":"8f4eb30f-762a-11e2-b1f5-4040782fde00","tax_id":"ce5596ad-a177-11e2-b2b4-bc764e10976c"}],"categories":[]}';
		$item = json_decode($json);
		$product = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $item->id));
		if ($product == null) {
			$product = new ArchProduct();
			$product
			->setId(@$item->id)
			->setIsActive(0)
			;
		}
		
		$product
		->setSku(@$item->sku)
		->setHandle(@$item->handle)
		->setVariantParentId(empty($item->variant_parent_id) ? null : $item->variant_parent_id)
		->setName(@$item->name)
		->setBaseName(@$item->base_name)
		->setVendActive(@$item->active)
		->setProductType(@$item->product_type->name)
		->setTags(@$item->tags)
		->setDescription(@$item->description)
		->setBrandName(@$item->brand->name)
		->setSupplierName(@$item->supplier->name)
		->setSupplyPrice(@$item->supply_price)
		->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")))
		;
		
		if (isset($item->deleted_at))
			$product->setDeletedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $item->deleted_at));
			
			if (isset($item->variant_options)) {
				$ctr = 0;
				foreach ($item->variant_options as $variant) {
					switch ($ctr) {
						case 0:
							$product
							->setVariantOptionOneName(@$variant->variant_option_one_name)
							->setVariantOptionOneValue(@$variant->variant_option_one_value);
							break;
						case 1:
							$product
							->setVariantOptionTwoName(@$variant->variant_option_two_name)
							->setVariantOptionTwoValue(@$variant->variant_option_two_value);
							break;
						case 2:
							$product
							->setVariantOptionThreeName(@$variant->variant_option_three_name)
							->setVariantOptionThreeValue(@$variant->variant_option_three_value);
							break;
					}
					$ctr++;
				}
			}
			
			
			if (isset($item->price_book_entries)) {
				foreach ($item->price_book_entries as $price_book) {
					$product
					->setRetailPrice(@$price_book->price) // retail_price = price when getting, retail_price = retail_price when posting w/o tax
					->setPrice((@$price_book->price + @$price_book->tax))
					->setTax(@$price_book->tax)
					;
					break;
				}
			}
			else
				$product->setTax(0);
				
				if (isset($item->taxes)) {
					$default_tax = $em->getRepository('AppBundle:ArchTax')->findOneBy(array('is_default' => 1))->getId();
					foreach ($item->taxes as $tax) {
						if ($tax->tax_id == $default_tax) {
							$product
							->setTaxId(@$tax->tax_id)
							;
						}
					}
				}
				
				if (isset($item->inventory)) {
					$default_outlet = $em->getRepository('AppBundle:ArchOutlet')->findOneBy(array('is_default' => 1))->getId();
					foreach ($item->inventory as $inventory) {
						if ($inventory->outlet_id == $default_outlet) {
							$product
							->setOutletId($inventory->outlet_id)
							->setCount(isset($inventory->count) ? $inventory->count : 0)
							->setReorderPoint(isset($inventory->reorder_point) ? $inventory->reorder_point : 0)
							->setRestockLevel(isset($inventory->restock_level) ? $inventory->restock_level : 0)
							;
						}
					}
				}
				
				if (isset($item->brand->name)) {
					$check = $em->getRepository('AppBundle:ArchProductBrand')->findOneBy(array('name' => $item->brand->name));
					if ($check == null) {
						$brand = new ArchProductBrand();
						$brand
						->setName($item->brand->name)
						->setIsActive(1);
						
						$em->persist($brand);
					}
				}
				
				if (isset($item->product_type->name)) {
					$check = $em->getRepository('AppBundle:ArchProductType')->findOneBy(array('name' => $item->product_type->name));
					if ($check == null) {
						$product_type = new ArchProductType();
						$product_type
						->setName($item->product_type->name)
						->setIsActive(1);
						
						$em->persist($product_type);
					}
				}
				
				
				$em->persist($product);
				$em->flush();die;
	}
}