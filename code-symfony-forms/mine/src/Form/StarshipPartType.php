<?php

namespace App\Form;

use App\Entity\Starship;
use App\Entity\StarshipPart;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StarshipPartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('notes')
            ->add('starship', EntityType::class, [
                'class' => Starship::class,
                'choice_label' => function (Starship $starship) {
                    return sprintf(
                        '%s (by %s)',
                        $starship->getName(),
                        $starship->getCaptain(),
                    );
                },
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('starship')
                        ->orderBy('starship.name', Order::Ascending->value);
                },
            ])
            ->add('createAndAddNew', SubmitType::class, [
                'attr' => [
                    'class' => 'text-white bg-blue-700 hover:bg-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2 cursor-pointer',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StarshipPart::class,
        ]);
    }
}
