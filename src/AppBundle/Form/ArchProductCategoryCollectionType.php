<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchProductCategoryCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('collection',EntityType::class,
        		array(
        				'label' => false,
        				'class' => 'AppBundle:ArchProductCollection',
        				'choice_label' => 'name',
        				'choice_value' => 'id',
        				'placeholder' => 'Choose one',
        		)
        	)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductCategoryCollection',
        ));
    }
}