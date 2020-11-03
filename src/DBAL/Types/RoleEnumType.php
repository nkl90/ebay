<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\Exception\InvalidArgumentException;

final class RoleEnumType extends AbstractEnumType
{

    public const ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";

    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_MANAGER = 'ROLE_MANAGER';
    
    public const ROLE_USER= 'ROLE_USER';

    protected static $choices = [
        self::ROLE_MANAGER => 'Менеджер',
        self::ROLE_ADMIN => 'Админ',
        self::ROLE_SUPER_ADMIN => "Супер-админ",
        self::ROLE_USER=>"Пользователь"
    ];

}