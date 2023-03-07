<?php

namespace App\Model\Enum;

enum UserRolesEnum: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
}