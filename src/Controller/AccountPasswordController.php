<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as ORMEntityManagerInterface;

class AccountPasswordController extends AbstractController
{

   private $entityManager;

   public function __construct(ORMEntityManagerInterface $entityManager){
    $this->entityManager = $entityManager;
   }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification_Good_Pwd = null;
        $notification_Wrong_Pwd = null;

        $user =$this->getUser();
        $form =$this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Methode permettant de comparer le mot de passe utilisateur au mot de passe BD.

            $old_pwd = $form->get('old_password')->getData();
           // dd($old_pwd);
            if ($encoder->isPasswordValid($user, $old_pwd)){

                $new_pwd =$form->get('new_password')->getData();
                //die('CA MARCHE');
                $password = $encoder->hashPassword($user, $new_pwd);

                $user->setPassword($password);
                //mettre à jour en BD
                $this->entityManager-> flush();
                $notification_Good_Pwd = "Votre mot de passe a bien été mis à jour.";
            } else {
                $notification_Wrong_Pwd = "Votre mot de passe actuel n'est pas le bon.";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification_Gp'=> $notification_Good_Pwd,
            'notification_Wp'=> $notification_Wrong_Pwd
        ]);
    }
}
