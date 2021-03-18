<?php

namespace App\Form;

use App\Form\DataTransformer\NameToPokemonTransformer;
use App\Repository\PokemonRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class PokemonSelectTextType extends AbstractType
{
    private $pokemonRepository;
    private $router;

    public function __construct(PokemonRepository $pokemonRepository, RouterInterface $router)
    {
        $this->pokemonRepository = $pokemonRepository;
        $this->router = $router;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new NameToPokemonTransformer($this->pokemonRepository));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'Hmm, ce pokémon n\'existe pas !',
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('app_utility_pokemons')
            ]
        ]);
    }
        
    public function getParent()
    {
        return TextType::class;
    }
}
