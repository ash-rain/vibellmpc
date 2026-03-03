<?php

declare(strict_types=1);

namespace VibellmPC\Common\Enums;

enum WizardStep: string
{
    case Welcome = 'welcome';
    case ModelSelection = 'model_selection';
    case TunnelSetup = 'tunnel_setup';
    case Complete = 'complete';
}
