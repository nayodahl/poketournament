<?php

namespace App\Form;

use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class TournamentType extends AbstractType
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Choisis un nom pour ton tournoi :',
                'attr' => ['autofocus' => true],
            ])
            ->add('numberPokemons', ChoiceType::class, [
                'label' => 'Choisis le nombre de participants :',
                'empty_data' => '8',
                'data' => '8',
                'choices' => [
                    '2' => 2,
                    '4' => 4,
                    '8' => 8,
                    '16' => 16,
                ],
                'attr' => [
                    'disabled' => true,
                ],
            ])
            /*
            ->add('pokemons', CollectionType::class, [
                'entry_type' => PokemonSelectTextType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            */
            ->add('pokemon1', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],
            ])
            ->add('pokemon2', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],            ])
            ->add('pokemon3', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],            ])
            ->add('pokemon4', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],            ])
            ->add('pokemon5', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],            ])
            ->add('pokemon6', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],            ])
            ->add('pokemon7', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],            ])
            ->add('pokemon8', PokemonSelectTextType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'js-pokemon-autocomplete',
                    'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
                ],            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
