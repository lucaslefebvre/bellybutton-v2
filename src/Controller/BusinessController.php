<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterBusinessType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BusinessController extends AbstractController
{
    /**
     * @Route("/business", name="business", methods={"GET"})
     */
    public function business()
    {
        return $this->render('business/business.html.twig');
    }
    /**
     * @Route("/business/register", name="business_register", methods={"GET", "POST"})
     */
    public function register(Request $request, RoleRepository $roleRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterBusinessType::class, $user);
        $form->handleRequest($request);
        $roleBusiness = $roleRepository->findOneBy(['name' => 'ROLE_BUSINESS']);
        if($form->isSubmitted() && $form->isValid()){
            $user->addUserRole($roleBusiness);
            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $em->persist($user);
            $em->flush();
            dump($user);

            return $this->redirectToRoute('business_success');
        }

        return $this->render('business/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/business/success", name="business_success", methods={"GET"})
     */
    public function success()
    {
        return $this->render('business/success.html.twig');
    }
}
