<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ArchProductInventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('outlet_id', EntityType::class, array(
	        		'label' => 'Outlet*',
	        		'class' => 'AppBundle:ArchOutlet',
	        		'choice_label' => 'name',
	        		'choice_value' => 'id',
	        		'placeholder' => 'Choose one'
	        ))
	        ->add('count', NumberType::class, array(
            	'label' => 'Count*',
            ))
            ->add('reorder_point', NumberType::class, array(
            		'required' => false
            ))
            ->add('restock_level', NumberType::class, array(
            		'required' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductInventory',
        ));
    }
}