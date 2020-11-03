<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\DBAL\Types\RoleEnumType;



class RegistrationController extends AbstractController
{
    private $entityManager;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function Registration(AuthenticationUtils $authenticationUtils)
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        
        $error ="skip";
    
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('registration/registration.html.twig',  ['last_username' => $lastUsername, 'error' => $error]);
    }
  
    public function valid(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $credentials=$_POST['email'];
        $user = $this->entityManager->getRepository(User::class)->findBy(['email' => $credentials]);
        $password=$_POST['password'];
        if ($_POST['password']!=$_POST['repeat_password'])
        {
            $lastUsername = $_POST['email'];
            $error='Пароли не совпадают';
            if ($user)
                echo "Почта занята";
            return $this->render('registration/registration.html.twig',  ['last_username' => $lastUsername, 'error' => $error]);
          
                
        }
        else if ($user){
            $lastUsername = $_POST['email'];
            $error='Почта занята';
            return $this->render('registration/registration.html.twig',  ['last_username' => $lastUsername, 'error' => $error]);
        }
        else
        {   
            $adduser = $this->prepareObject();
            $adduser
            ->setEmail($credentials)
            ->setRoles([
                RoleEnumType::ROLE_USER
            ]);
            $createuser=$this->getDoctrine()->getManager();
            $createuser->persist($adduser);
            $createuser->flush();
            return $this->render('registration/confirm.html.twig');
        
        }
            
            
    }
 
    
  
    private function prepareObject()
    {   $password=$_POST['password'];
        $o = new User();
        $o->setPassword($this->passwordEncoder->encodePassword(
            $o,
            $password
            ));
        return $o;
    }
    
}
