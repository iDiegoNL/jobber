<?php

namespace App\Enums;

use ArchTech\Enums\Metadata;
use ArchTech\Enums\Meta\Meta;
use App\Enums\MetaProperties\{Description};

#[Meta(Description::class)]
enum CompanyEmployeeCount: int
{
    use Metadata;

    #[Description('1-10 employees')]
    case LessThan10 = 1;

    #[Description('10-50 employees')]
    case Between10And50 = 2;

    #[Description('50-100 employees')]
    case Between50And100 = 3;

    #[Description('100-500 employees')]
    case Between100And500 = 4;

    #[Description('500-1000 employees')]
    case Between500And1000 = 5;

    #[Description('Over 1000 employees')]
    case Over1000 = 6;
}
