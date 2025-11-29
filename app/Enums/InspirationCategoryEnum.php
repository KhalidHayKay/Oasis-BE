<?php

namespace App\Enums;

enum InspirationCategoryEnum: string
{
    case All        = 'All';
    case Bedroom    = 'Bedroom';
    case LivingRoom = 'Living room';
    case Kitchen    = 'Kitchen';
    case Workspace  = 'Workspace';
    case Outdoor    = 'Outdoor';
    case Bathroom   = 'Bathroom';
    case HomeOffice = 'Home office';
    case Dining     = 'Dining';
}
