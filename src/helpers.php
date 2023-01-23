<?php

use TRegx\CleanRegex\Pattern;

if (!function_exists('pattern')) {
    function pattern(string $pattern, string $modifiers = null): Pattern
    {
        return Pattern::of($pattern, $modifiers);
    }
}

function pattern_test(string $pattern, string $subject, string $modifiers = ''): bool
{
    return Pattern::of($pattern, $modifiers)->test($subject);
}

function pattern_fails(string $pattern, string $subject, string $modifiers = ''): bool
{
    return Pattern::of($pattern, $modifiers)->fails($subject);
}

/**
 * @param string $pattern
 * @param string[] $subjects
 * @param string $modifiers
 * @return string[]
 */
function pattern_filter(string $pattern, array $subjects, string $modifiers = ''): array
{
    return Pattern::of($pattern, $modifiers)->filter($subjects);
}
