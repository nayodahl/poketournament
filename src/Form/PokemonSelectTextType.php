<?php

namespace App\Form;

use App\Form\DataTransformer\NameToPokemonTransformer;
use App\Repository\PokemonRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PokemonSelectTextType extends AbstractType
{
    private $pokemonRepository;

    public function __construct(PokemonRepository $pokemonRepository)
    {
        $this->pokemonRepository = $pokemonRepository;
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
            'attr', [
                'class' => 'pokemon-input'
            ]
        ]);
    }
        
    public function getParent()
    {
        return TextType::class;
    }
}