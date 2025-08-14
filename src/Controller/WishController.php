<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy([], ['dateCreated' => 'DESC']);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'],methods: ['GET'] )]
    public function detail(int $id, WishRepository $wishRepository): Response
    {

        $wishes = $wishRepository->find($id);

        if (!$wishes) {
            throw $this->createNotFoundException('Wish non trouvé');
        }

        return $this->render('wish/detail.html.twig',[
            'wishes' => $wishes,
        ]);
    }
}

