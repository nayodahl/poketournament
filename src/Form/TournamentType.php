<?php

namespace App\Form;

use App\Entity\Pokemon;
use App\Entity\Tournament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Choisis un nom pour ton tournoi :',
                'attr' => ['autofocus' => true],
            ])
            ->add('numberPokemons', IntegerType::class, [
                'label' => 'Choisis le nombre de participants :',
                'empty_data' => '8',
                'data' => '8',
            ])
            ->add('pokemons', EntityType::class, [
                'label' => false,
                'class' => Pokemon::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
