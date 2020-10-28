<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\DBAL\Types\RoleEnumType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * В этом классе реализуются шаблонные виджеты
 *
 * @author nik
 *        
 */
class WidgetsExtension extends AbstractExtension
{
    use ControllerTrait;

    private $container;
    private $em;
    private $checkerService;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getFunctions()
    {
        return [
            // рендерит список ролей пользователей с человеко-понятными названиями
            new TwigFunction('renderUserRoles', [
                $this,
                'renderUserRoles'
            ])
        ];
    }

    public function renderUserRoles()
    {
        $user = $this->getUser();
        $roleNames = array_flip(RoleEnumType::getChoices());

        $roles = [];
        foreach ($user->getRoles() as $role) {
            if ($role == 'ROLE_USER') { // эта системная роль, её показывать не нужно
                continue;
            }
            $roles[$role] = $roleNames[$role];
        }

        return $this->renderView('widget/user_roles.html.twig', [
            'roles' => $roles
        ]);
    }
    
}