<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Program;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyPath;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProgramType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add(
                'synopsis',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add('year', IntegerType::class, [
                'required' => false,
                'attr'     => [
                    'min' => 1890,
                    'max' => 2500,
                ],
            ])
            ->add('country', EntityType::class, [
                'required'     => false,
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
            ])
            ->add('posterFile', VichImageType::class, [
                'required'       => false,
                'download_label' => new PropertyPath('poster'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
