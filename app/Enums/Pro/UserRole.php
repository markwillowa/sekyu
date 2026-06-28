<?php

namespace App\Enums\Pro;

enum UserRole: string
{
    case Owner = 'owner';

    case HR = 'hr';

    case Recruiter = 'recruiter';

    case Operations = 'operations';

    case Finance = 'finance';

    case Supervisor = 'supervisor';
}
