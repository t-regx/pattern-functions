<?php

use TRegx\CleanRegex\Pattern;

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $modifiers = null): Pattern
    {
        return Pattern::of($pattern, $modifiers);
    }
}
