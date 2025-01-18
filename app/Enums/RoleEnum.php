<?php


namespace App\Enums;

enum RoleEnum:string
 {
    case SUPER_ADMIN = 'super-admin';
    case TENANT_ADMIN = 'tenant-admin';
    case USER = 'tenant-user';
}