<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\DBAL\Types\RoleEnumType;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $admin = $this->prepareObject();
        $admin
            ->setEmail('admin@mail.ru')
            ->setRoles([
            RoleEnumType::ROLE_SUPER_ADMIN
        ]);
            
        $manager->persist($admin);
        $manager->flush();
    }
    
    private function prepareObject()
    {
        $o = new User();
        $o->setPassword($this->passwordEncoder->encodePassword(
            $o,
            'qwerty'
        ));
        return $o;
    }
}
