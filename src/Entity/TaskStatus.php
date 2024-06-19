<?php

namespace App\Entity;

enum TaskStatus: int
{
    case ToDo = 0;
    case InProgress = 1;
    case Done = 2;
}
