<?php

namespace App\Enums\Pro;

enum EmploymentStatus: string
{
    case Probationary = 'probationary';

    case Regular = 'regular';

    case Contractual = 'contractual';

    case Resigned = 'resigned';

    case Terminated = 'terminated';
}
