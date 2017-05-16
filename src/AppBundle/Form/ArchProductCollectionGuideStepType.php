<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArchProductCollectionGuideStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name', TextType::class, array(
	        		'label' => 'Step Title*',
	        ))
	        ->add('image', FileType::class, array(
	        		'data_class' => null,
	        		'required' => false,
	        		'label' => false,
	        ))
	        ->add('details', CKEditorType::class, array(
	        		'label' => 'Details*',
	        		'config' => array(
	        				'height' => '200px',
	        		),
	        ))
	        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductCollectionGuideStep',
        ));
    }
}