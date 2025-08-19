<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();

        $wishes = $wishRepository->findBy(
            [],
            [
                'dateCreated' => 'DESC',
                'title' => 'ASC',
            ]);
        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes,
        ]);
    }


    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {

        $wishes = $wishRepository->find($id);

        if (!$wishes) {
            throw $this->createNotFoundException('Wish non trouvé');
        }

        return $this->render('wish/detail.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);

        // Récupération des données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$wish->getDateCreated()) {
                $wish->setDateCreated(new \DateTimeImmutable());
            }


            $wish->setDateUpdated(new \DateTimeImmutable());

            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Un vœu a été enregistré');

            return $this->redirectToRoute('detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'wish_form' => $form->createView(),
            'wish' => $wish,
        ]);
    }
}