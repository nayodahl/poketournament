<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Service\Populator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PokemonController extends AbstractController
{
    #[Route('/pokemon', name: 'pokemon')]
    public function index(): Response
    {
        return $this->render('pokemon/index.html.twig', [
            'controller_name' => 'PokemonController',
        ]);
    }

    #[Route('/pokedex', name: 'app_pokedex')]
    public function pokedexShow(PokemonRepository $pokemonRepo): Response
    {
        return $this->render('pokemon/pokedex.html.twig', [
            'pokemons' => $pokemonRepo->findAll(),
        ]);
    }

    #[Route('/pokedex/{slug}', name: 'app_pokemon')]
    public function pokemonShow(PokemonRepository $pokemonRepo, string $slug): Response
    {
        $pokemon = $pokemonRepo->findOneBy([ 'slug' => $slug ]);
        if (isset($pokemon)) {
            $previous = $pokemonRepo->findPreviousByApiId($pokemon);
            $next = $pokemonRepo->findNextByApiId($pokemon);
        }

        $options = [
            'decorate' => true,
            'rootOpen' => '<ul class="evolutionchain">',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'nodeDecorator' => fn($node) => '<a href="/pokedex/'.$node['slug'].'">'
                    .'<div class="evolutionchain-name">'.$node['name'].' #'.$node['apiId'].'</div>'
                    .'<img class="evolutionchain-image" src="/images/'.$node['apiId'].'.png"></img>'
                    .'</a>'
        ];

        $evolutionChain = $pokemonRepo->childrenHierarchy(
            $pokemon?->getRoot(),
            false,
            $options,
            true
        );

        return $this->render('pokemon/pokemon.html.twig', [
            'pokemon' => $pokemon,
            'evolutionChain' => $evolutionChain,
            'previous' => $previous ?? null,
            'next' => $next ?? null,
            'isAlola' => file_exists('../public/images/' . $pokemon?->getApiId() . '-alola.png'),
            'isGalar' => file_exists('../public/images/' . $pokemon?->getApiId() . '-galar.png'),
            'isGmax' => file_exists('../public/images/' . $pokemon?->getApiId() . '-gmax.png'),
            'isMega' => file_exists('../public/images/' . $pokemon?->getApiId() . '-mega.png'),
            'isMegaX' => file_exists('../public/images/' . $pokemon?->getApiId() . '-mega-x.png'),
            'isMegaY' => file_exists('../public/images/' . $pokemon?->getApiId() . '-mega-y.png'),
            ]);
    }


    #[Route('/utility/pokemons', name: 'app_utility_pokemons')]
    public function findPokemonsApi(PokemonRepository $pokemonRepo, Request $request): Response
    {
        $list = $pokemonRepo->findAllAlphabeticalMatching((string)($request->query->get('query')));

        return $this->json(['pokemons' => $list], 200, [], ['groups' => ['list_pokemon']]);
    }


    #[Route('/utility/pokedex', name: 'app_utility_pokedex')]
    public function findAllPokemonsApi(PokemonRepository $pokemonRepo): Response
    {
        $list = $pokemonRepo->findAll();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($list, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => [
            'id',
            'image',
            'tournaments',
            'root',
            'parent',
            'children',
        ]]);

        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/populate", name="app_populate")
     */
    /*
    public function loadDataFromPokeapi(Populator $populator): Response
    {
        $populator->populateSlug();

        return $this->redirectToRoute('app_homepage');
    }
    */
}
