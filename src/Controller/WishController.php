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
    // Liste des wishes publiés
    #[Route('/wishes', name: 'wish_list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }

    // Détail d’un wish
    #[Route('/wishes/{id}', name: 'wish_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        if (!$wish) {
            throw $this->createNotFoundException('Wish non trouvé');
        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }

    // Création d’un wish
    #[Route('/wishes/create', name: 'wish_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wish->setIsPublished(true);
            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Idea successfully added!');

            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm
        ]);
    }

    // Mise à jour d’un wish
    #[Route('/wishes/{id}/update', name: 'wish_update', requirements: ['id'=>'\d+'], methods: ['GET','POST'])]
    public function update(int $id, WishRepository $wishRepository, Request $request, EntityManagerInterface $em): Response
    {
        $wish = $wishRepository->find($id);
        if (!$wish){
            throw $this->createNotFoundException('This wish does not exist!');
        }

        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            $wish->setDateUpdated(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Idea successfully updated!');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm
        ]);
    }

    // Suppression d’un wish
    #[Route('/wishes/{id}/delete', name: 'wish_delete', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function delete(int $id, WishRepository $wishRepository, Request $request, EntityManagerInterface $em): Response
    {
        $wish = $wishRepository->find($id);
        if (!$wish){
            throw $this->createNotFoundException('This wish does not exist!');
        }

        if ($this->isCsrfTokenValid('delete'.$id, $request->query->get('token'))) {
            $em->remove($wish);
            $em->flush();
            $this->addFlash('success', 'This wish has been deleted');
        } else {
            $this->addFlash('danger', 'This wish cannot be deleted');
        }

        return $this->redirectToRoute('wish_list');
    }
}
