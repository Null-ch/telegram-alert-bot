<?php

namespace App\Enums;

enum ChatType: string
{
    case private = 'Личные сообщения';
    case group = 'Группа';
}
