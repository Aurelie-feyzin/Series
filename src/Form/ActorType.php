<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Program;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActorType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('programs', EntityType::class, [
                'class'        => Program::class,
                'choice_label' => 'title',
                'expanded'     => true,
                'multiple'     => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'empty_data' => function (FormInterface $form) {
                return new Actor($form->get('firstname')->getData(), $form->get('lastname')->getData());
            },
        ]);
    }
}
