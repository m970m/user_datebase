<?php
declare(strict_types=1);

namespace App\Database;

enum DbSourceType
{
    case MYSQL;
    case JSON;
}