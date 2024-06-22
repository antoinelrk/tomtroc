<?php

namespace App\Enum;

enum ColorsEnum: string
{
    case RESET = "\033[0m";

    // ---------- FOREGROUND ----------

    case LIGHT_RED = "\033[0;91m";
    case DARK_RED = "\033[0;31m";
    case LIGHT_GREEN = "\033[0;92m";
    case DARK_GREEN = "\033[0;32m";
    case LIGHT_BLUE = "\033[0;94m";
    case DARK_BLUE = "\033[0;34m";
    case LIGHT_ROSE = "\033[0;38;5;206m";
    case DARK_ROSE = "\033[0;38;5;170m";

    // ---------- BACKGROUND ----------

    case BG_LIGHT_RED = "\033[101m";
    case BG_DARK_RED = "\033[41m";
    case BG_LIGHT_GREEN = "\033[102m";
    case BG_DARK_GREEN = "\033[42m";
    case BG_LIGHT_BLUE = "\033[104m";
    case BG_DARK_BLUE = "\033[44m";
    case BG_LIGHT_MAGENTA = "\033[105m";
    case BG_DARK_MAGENTA = "\033[45m";
    case BG_LIGHT_ROSE = "\033[48;5;206m";
    case BG_DARK_ROSE = "\033[48;5;170m";
}
