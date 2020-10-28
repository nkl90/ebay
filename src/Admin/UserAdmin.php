<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\DBAL\Types\RoleEnumType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class UserAdmin extends AbstractAdmin
{
    protected $translationDomain = 'admin_user';
    protected $encoder;

    public function injectDepedencies(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('email')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id')
            ->add('email')
            ->add('roles')
       ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $requirePassword = false;
        $passwordHelpLabel = 'Fill in only if you need to change the password';
        
        if ($this->getRequest()->attributes->get('_route') == 'admin_app_user_create') {
            //$requirePassword = true;
            $passwordHelpLabel = '';
        }
        
        $formMapper
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => RoleEnumType::getChoices(),
                'multiple' => true,
            ])
            ->add('password', PasswordType::class, [
                'required' => $requirePassword,
                'help' => $passwordHelpLabel,
                'mapped' => false
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('email')
            ->add('roles')
            ->add('password')
            ;
    }
    
    private function encodePlainPassword($user)
    {
        $plainPassword = $this->getRequest()->request->get($this->uniqid)['password'];
        
        $user->setPassword($this->encoder->encodePassword(
            $user,
            $plainPassword
        ));
    }
    
    public function preUpdate($user)
    {
        if ($this->getRequest()->request->get($this->uniqid)['password'] !== '') {
            $this->encodePlainPassword($user);
        }
        
    }
    
    public function prePersist($user)
    {
        $this->encodePlainPassword($user);
    }
}
