<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArchPageAboutSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name', TextType::class, array('label' => 'Section Title', 'required' => false))
	        ->add('filename', FileType::class, array(
	        		'data_class' => null,
	        		'label' => 'Side Image',
	        		'required' => false
	        ))
	        ->add('content', CKEditorType::class, array(
	        		'label' => 'Content',
	        		'required' => false
	        ))
	    ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageAboutSection',
        ));
    }
}