<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArchPageAboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
            	'label' => 'Title',
            	'required' => true
            ))
            ->add('content', CKEditorType::class, array(
            		'label' => 'Content',
            		'required' => false
            ))
            ->add('thumbnail', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Side Image',
            ))
            ->add('banner', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Banner',
            ))
            ->add('banner_text', TextareaType::class, array('required' => false))
            ->add('banner_text_color', ChoiceType::class, array(
            		'label' => 'Banner text colour',
            		'choices' => array(
            				'Black' => true,
            				'White' => false,
            		),
            		'placeholder' => false,
            		'expanded' => true,
            		'multiple' => false,
            		'required' => false
            ))
            ->add('banner_overlay', CheckboxType::class, array(
            		'label' => 'Dark overlay',
            		'required' => false
            ))
            ->add('sections', CollectionType::class, array(
            		'entry_type'    => ArchPageAboutSectionType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => ' ',
            ))
            ->add('meta_title', TextType::class, array(
            		'label' => 'Meta Title',
            		'required' => false
            ))
            ->add('meta_description', TextareaType::class, array(
            		'label' => 'Meta Description',
            		'required' => false
            ))
            
            ->add('save', SubmitType::class, array('label' => 'Save Changes'))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageAbout',
        ));
    }
}