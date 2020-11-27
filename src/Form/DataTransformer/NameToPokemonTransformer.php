<?php

namespace App\Form\DataTransformer;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NameToPokemonTransformer implements DataTransformerInterface
{
    private $pokemonRepository;

    public function __construct(PokemonRepository $pokemonRepository)
    {
        $this->pokemonRepository = $pokemonRepository;
    }
    
    public function transform($value)
    {
        //dd('transform', $value);
        if (null === $value) {
            return '';
        }
        if (!$value instanceof Pokemon) {
            throw new \LogicException('The PokemonSelectTextType can only be used with User objects');
        }
        return $value->getName();
    }

    public function reverseTransform($value)
    {
        //dd('reverseTransform', $value);
        $pokemon = $this->pokemonRepository->findOneBy(['name' => $value]);
        if (!$pokemon) {
            throw new TransformationFailedException(sprintf('No pokemon found with name "%s"', $value));
        }
        return $pokemon;
    }
}
