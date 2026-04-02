<?php

namespace App\Controller;

use App\Entity\Pregnancy;
use App\Entity\Store;
use App\Entity\User;
use App\Form\Type\PregnancyType;
use App\Form\Type\StoreType;
use App\Form\Type\UserType;
use App\Repository\PregnancyRepository;
use App\Repository\StoreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/administration')]
final class AdministrationController extends AbstractController
{
    #[Route('', name: 'app_administration')]
    public function index(
        Request $request,
        PregnancyRepository $pregnancyRepository,
        StoreRepository $storeRepository,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Grossesse
        $pregnancy = $pregnancyRepository->getOrCreateCurrent();
        $pregnancyForm = $this->createForm(PregnancyType::class, $pregnancy);
        $pregnancyForm->handleRequest($request);

        if ($pregnancyForm->isSubmitted() && $pregnancyForm->isValid()) {
            $em->persist($pregnancy);
            $em->flush();
            $this->addFlash('success', 'Informations de grossesse mises à jour ✅');

            return $this->redirectToRoute('app_administration');
        }

        // Plateformes
        $stores = $storeRepository->findAll();
        $newStore = new Store();
        $storeForm = $this->createForm(StoreType::class, $newStore);
        $storeForm->handleRequest($request);

        if ($storeForm->isSubmitted() && $storeForm->isValid()) {
            $em->persist($newStore);
            $em->flush();
            $this->addFlash('success', 'Plateforme ajoutée ✅');

            return $this->redirectToRoute('app_administration');
        }

        // Utilisateurs
        $users = $userRepository->findAll();
        $adminUser = $users[0] ?? null;
        $userForm = null;

        if ($adminUser) {
            $userForm = $this->createForm(UserType::class, $adminUser);
            $userForm->handleRequest($request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {
                if ($userForm->get('password')->getData()) {
                    $hashedPassword = $passwordHasher->hashPassword(
                        $adminUser,
                        $userForm->get('password')->getData()
                    );
                    $adminUser->setPassword($hashedPassword);
                }
                $em->persist($adminUser);
                $em->flush();
                $this->addFlash('success', 'Informations utilisateur mises à jour ✅');

                return $this->redirectToRoute('app_administration');
            }
        }

        return $this->render('administration/index.html.twig', [
            'pregnancy_form' => $pregnancyForm,
            'pregnancy' => $pregnancy,
            'store_form' => $storeForm,
            'stores' => $stores,
            'user_form' => $userForm,
            'admin_user' => $adminUser,
        ]);
    }

    #[Route('/store/{id}/delete', name: 'app_administration_store_delete', requirements: ['id' => '\d+'])]
    public function deleteStore(
        Store $store,
        EntityManagerInterface $em
    ): Response {
        $em->remove($store);
        $em->flush();

        $this->addFlash('success', 'Plateforme supprimée ✅');

        return $this->redirectToRoute('app_administration');
    }

    #[Route('/store/{id}/edit', name: 'app_administration_store_edit', requirements: ['id' => '\d+'])]
    public function editStore(
        Store $store,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($store);
            $em->flush();

            $this->addFlash('success', 'Plateforme mise à jour ✅');

            return $this->redirectToRoute('app_administration');
        }

        return $this->render('administration/store_edit.html.twig', [
            'form' => $form,
            'store' => $store,
        ]);
    }
}
