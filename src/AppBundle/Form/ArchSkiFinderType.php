<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchSkiFinderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('technical_ability', ChoiceType::class, array(
            		'choices' => array(
            				'criteria' => array(
            						'Beginner: I won’t be uploading to social just yet' => 1,
            						'Intermediate: I look tidy on moderate trails but steeper gradients get the better of me' => 2,
            						'Advanced: I ski well in most conditions but tricky snow catches me out' => 3,
            						'Expert: I should be/was a ski instructor. Technically correct in all terrain, in all snow conditions' => 4
            				)
            		),
            		'multiple' => true,
            		'expanded' => true
            ))
            ->add('experience', ChoiceType::class, array(
            		'choices' => array(
	            			'criteria' => array(
	            				"Limited: I'm really just starting out" => 1,
	            				"So-so:  I have skied at least one NZ season" => 2,
	            				"Oodles:  I have a few season passes to prove it" => 3,
	            				"Colossal: These grey hairs can tell stories of the best pow days" => 4
            				)
            		),
            		'multiple' => true,
            		'expanded' => true
            ))
            ->add('approach', ChoiceType::class, array(
            		'choices' => array(
            				'criteria' => array(
	            				"Cautious: I like to stay in one piece" => 1,
	            				"Dabbler: When I’m feeling it, I’ll open it up" => 2,
	            				"Lion Cub: I usually ski hard but it's nice to chill at times" => 3,
	            				"Lion King: I'm adrenaline loaded and constantly crank high speed turns" => 4
            				)
            		),
            		'multiple' => true,
            		'expanded' => true
            ))
            ->add('favourite_hangouts', ChoiceType::class, array(
            		'choices' => array(
            				'criteria' => array(
	            				"North Island Resorts" => 1,
	            				"South Island Resorts" => 2,
	            				"The Clubbies" => 3,
	            				"Nth America" => 4,
	            				"Japan" => 5
            				)
            		),
            		'multiple' => true,
            		'expanded' => true
            ))
            ->add('favourite_flavour', ChoiceType::class, array(
            		'choices' => array(
            				'criteria' => array(
            						"Groomers: I love to hang out on prepared trails" => 1,
		            				"50/50: I'll go off-piste if conditions are good but I'm just as happy on groomers" => 2,
		            				"Freeride:  I only ski on groomers for a warmup or if the snow is shockingly bad" => 3,
		            				"Powder: These bad boys are for Japan, North America or those epic days in NZ" => 4,
		            				"Backcountry: I'm looking for something lighter that I can mount with touring bindings" => 5
            				)
            		),
            		'multiple' => true,
            		'expanded' => true
            ))
            ->add('specialist', ChoiceType::class, array(
            		'label' => 'Specialist or All  Rounder?',
            		'choices' => array(
            				'criteria' => array(
            						"Yes, this will be my only pair" => 1,
		            				"No, I’m adding these to my quiver" => 2,
            				)
            		),
            		'multiple' => true,
            		'expanded' => true
            ))
            ->add('fat_or_skinny', ChoiceType::class, array(
            		'choices' => array(
            				'criteria' => array(
            						"I'll leave it to your expert advice" => 1,
		            				"70-80mm" => 2,
		            				"81-90mm" => 3,
		            				"91-100mm" => 4,
		            				"101-110mm" => 5,
		            				"111+" => 6
            				)
            		),
            		'multiple' => true,
            		'expanded' => true
            ))
            
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchSkiFinder',
        ));
    }
}