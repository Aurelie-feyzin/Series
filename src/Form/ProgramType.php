<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Program;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('synopsis')
            ->add('poster')
            ->add('year')
            ->add('slug')
            ->add('country', EntityType::class, [
                'class'        => Country::class,
                'choice_label' => 'name',
                'expanded'     => false,
                'multiple'     => false,
            ])
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'expanded'     => false,
                'multiple'     => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
