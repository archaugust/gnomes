<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArchProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('base_name', TextType::class, array('label' => 'Name*'))
            ->add('handle', TextType::class, array('label' => 'Handle*'))
            ->add('description', CKEditorType::class, array('label' => 'Description', 'required' => false))
            ->add('features', CKEditorType::class, array('label' => 'Features', 'required' => false))
            ->add('product_type',EntityType::class,
           		array(
           				'label' => 'Type',
           				'class' => 'AppBundle:ArchProductType',
           				'query_builder' => function (EntityRepository $er) {
	           				return $er->createQueryBuilder('p')
	           				->orderBy('p.name', 'ASC');
           				},
           				'choice_label' => 'name',
           				'choice_value' => 'name',
           				'placeholder' => 'Choose one',
           				'required' => false,
           		)
            )
            ->add('is_active', ChoiceType::class, array(
            		'label' => 'Active',
            		'choices' => array(
            				'Yes' => true,
            				'No' => false
            		),
            ))
            ->add('pre_sell', ChoiceType::class, array(
            		'label' => 'Pre-selling',
            		'choices' => array(
            				'No' => false,
            				'Yes' => true,
            		),
            ))
            ->add('tags', TextType::class, array(
            		'required' => false,
            		'attr'=> array('class'=>'tags')
            ))
            ->add('video', TextareaType::class, array(
            		'required' => false,
            		'label' => 'Video embed HTML'
            ))
            ->add('sku', TextType::class, array('required' => false, 'label' => 'SKU'))
            ->add('meta_title', TextType::class, array(
            		'label' => 'Meta Title',
            		'required' => false
            ))
            ->add('meta_description', TextareaType::class, array(
            		'label' => 'Meta Description',
            		'required' => false
            ))
            ->add('variant_option_one_name', EntityType::class, array(
            		'label' => 'Option 1',
            		'required' => false,
            		'class' => 'AppBundle:ArchProductOption',
            		'choice_label' => 'name',
            		'choice_value' => 'name',
            		'placeholder' => 'Choose one'
            ))
            ->add('variant_option_one_value', TextType::class, array('required' => false, 'label' => 'Default Value'))
            ->add('variant_option_two_name', EntityType::class, array(
            		'label' => 'Option 2',
            		'required' => false,
            		'class' => 'AppBundle:ArchProductOption',
            		'choice_label' => 'name',
            		'choice_value' => 'name',
            		'placeholder' => 'Choose one'
            ))
            ->add('variant_option_two_value', TextType::class, array('required' => false, 'label' => 'Default Value'))
            ->add('variant_option_three_name', EntityType::class, array(
            		'label' => 'Option 3',
            		'required' => false,
            		'class' => 'AppBundle:ArchProductOption',
            		'choice_label' => 'name',
            		'choice_value' => 'name',
            		'placeholder' => 'Choose one'
            ))
            ->add('variant_option_three_value', TextType::class, array('required' => false, 'label' => 'Default Value'))
            ->add('supply_price', TextType::class, array('label' => 'Supply Price*'))
            ->add('retail_price', TextType::class, array('label' => '= Retail Price'))
            ->add('price', TextType::class, array('label' => '= Price*'))
            ->add('tax', EntityType::class, array(
            		'label' => '+ Tax*',
            		'class' => 'AppBundle:ArchTax',
            		'query_builder' => function(EntityRepository $i) {
            			return $i->createQueryBuilder('i')->where('i.is_active = 1')->orderBy('i.is_default','DESC');
            		},
            		'choice_label' => 'nameRateLabel',
            		'choice_value' => 'nameRateValue',
            ))
            ->add('brand_name', EntityType::class, array(
            		'label' => 'Brand Name',
            		'class' => 'AppBundle:ArchProductBrand',
            		'query_builder' => function (EntityRepository $er) {
	            		return $er->createQueryBuilder('p')
	            		->orderBy('p.name', 'ASC');
            		},
            		'choice_label' => 'name',
            		'choice_value' => 'name',
            		'data' => $options['data']->getBrandName(),
            		'placeholder' => 'Choose one', 
            		'required' => false
            ))
            ->add('supplier_name', EntityType::class, array(
            		'label' => 'Supplier Name',
            		'class' => 'AppBundle:ArchSupplier',
            		'query_builder' => function (EntityRepository $er) {
	            		return $er->createQueryBuilder('p')
	            		->orderBy('p.name', 'ASC');
            		},
            		'choice_label' => 'name',
            		'choice_value' => 'name',
            		'data' => $options['data']->getSupplierName(),
            		'placeholder' => 'Choose one',
            		'required' => false
            ))
            ->add('count', NumberType::class, array(
            		'label' => 'Count*',
            ))
            ->add('reorder_point', NumberType::class, array(
            		'required' => false
            ))
            ->add('restock_level', NumberType::class, array(
            		'required' => false
            ))
            ->add('size_guide', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Sizing Chart Image',
            ))
            
            ->add('images', CollectionType::class, array(
            		'entry_type'    => ArchProductImageType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => ' ',
            ))
            
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProduct',
        	'allow_extra_fields' => true
        ));
    }
}