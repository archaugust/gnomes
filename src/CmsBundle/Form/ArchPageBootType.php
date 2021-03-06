<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ArchPageBootType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intro', CKEditorType::class, array(
            		'label' => 'Intro',
            		'config' => array(
            				'height' => '100px',
            		),
            		'required' => false
            ))
            ->add('banner', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Banner',
            ))
            ->add('banner_text', TextareaType::class, array('label'=> 'Banner Text', 'required' => false))
            ->add('banner_text_color', ChoiceType::class, array(
            		'label' => 'Banner Text Colour',
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
            ->add('content_top', CKEditorType::class, array(
            		'label' => 'Content - Top',
            		'required' => false
            ))
            ->add('content_top_image', FileType::class, array(
            		'data_class' => null,
            		'label' => 'Image',
            		'required' => false
            ))
            ->add('content_middle', CKEditorType::class, array(
            		'label' => 'Content - Middle',
            		'required' => false
            ))
            ->add('content_middle_image', FileType::class, array(
            		'data_class' => null,
            		'label' => 'Image',
            		'required' => false
            ))
            ->add('prices', CollectionType::class, array(
            		'entry_type'    => ArchPageBootPriceType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => ' ',
            ))
            ->add('sections', CollectionType::class, array(
            		'entry_type'    => ArchPageBootSectionType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => ' ',
            ))
            ->add('booking', CKEditorType::class, array(
            		'label' => 'Bookings & Queries',
            		'config' => array(
            				'height' => '100px',
            		),
            		'required' => false
            ))
            ->add('meta_title', TextType::class, array(
       				'label' => 'Meta Title',
      				'required' => false
       		))
       		->add('meta_description', TextareaType::class, array(
       				'label' => 'Meta Description',
       				'required' => false
       		))
       		->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageBoot',
        	'allow_extra_fields' => true
        ));
    }
}