<?php

namespace AppBundle\Form\Membre;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class MembreEditType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('enabled', ChoiceType::class, array(
                'label' => 'Activé',
                'choices' => array(
                    'Oui' => 1,
                    'Non' => 0,
                ),
                'expanded' => true,
			))
            ->add('banni', ChoiceType::class, array(
                'label' => 'Banni',
                'choices' => array(
                    'Oui' => 1,
                    'Non' => 0,
                ),
                'expanded' => true,
            ))
            ->add('commentaireBan', TextType::class, array(
            ))
            ->add('dateDeban', DateTimeType::class, array(
                'data' => (new \DateTime())->add(new \DateInterval('P1D'))
            ))
            ->add('renamable', ChoiceType::class, array(
                'label' => 'Renomable',
                'choices' => array(
                    'Oui' => 1,
                    'Non' => 0,
                ),
                'expanded' => true,
            ))
            ->add('signale', ChoiceType::class, array(
                'label' => 'Signalé',
                'choices' => array(
                    'Oui' => 1,
                    'Non' => 0,
                ),
                'expanded' => true,
            ))
            ->add('modifier', SubmitType::class, array(
                'label' => 'Modifier',
                'attr' => array('class' => 'btn btn-warning'),
            ));
	}

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Membre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'AppBundle_membre';
    }

}
