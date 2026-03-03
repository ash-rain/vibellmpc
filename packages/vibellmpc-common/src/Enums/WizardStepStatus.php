<?php

declare(strict_types=1);

namespace VibellmPC\Common\Enums;

enum WizardStepStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Skipped = 'skipped';
}
