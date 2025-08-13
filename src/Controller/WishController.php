<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $wishes = [
            1 => 'Devenir riche Mother F****r !',
            2 => 'Vendre des photos de pieds',
            3 => 'Utiliser Ubuntu pour envoyer une fusée dans l\'espace'
        ];

        return $this->render('wish/list.html.twig', ['wishes' => $wishes]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(int $id): Response
    {

        $wishesDetails = [
            1 => 'Je veux devenir riche très vite pour vivre la meilleure vie possible.',
            2 => 'Je veux lancer une boutique en ligne vendant des photos originales.',
            3 => 'Je veux apprendre Ubuntu pour programmer une fusée spatiale.'
        ];

        if (!isset($wishesDetails[$id])) {
            throw $this->createNotFoundException('Wish non trouvé.');
        }

        return $this->render('wish/detail.html.twig', [
            'id' => $id,
            'detailText' => $wishesDetails[$id],
        ]);
    }
}