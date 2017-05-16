<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArchPageBootSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name', TextType::class, array('label' => 'Title', 'required' => false))
	        ->add('content', CKEditorType::class, array(
	        		'label' => 'Content',
	        		'config' => array(
	        				'height' => '200px',
	        		),
	        		'required' => false
	        ))
	        ->add('filename', FileType::class, array(
	        		'data_class' => null,
	        		'label' => 'Image',
	        		'required' => false
	        ))
	    ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageBootSection',
        ));
    }
}