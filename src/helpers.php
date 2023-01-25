<?php

use TRegx\CleanRegex\Match\Detail;
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

function pattern_match_first(string $pattern, string $subject, string $modifiers = ''): Detail
{
    return Pattern::of($pattern, $modifiers)->match($subject)->first();
}

function pattern_match_optional(string $pattern, string $subject, string $modifiers = ''): ?Detail
{
    return Pattern::of($pattern, $modifiers)->match($subject)->findFirst()->orReturn(null);
}

function pattern_match_ref(string $pattern, string $subject, ?Detail &$refDetail, string $modifiers = ''): bool
{
    $matcher = Pattern::of($pattern, $modifiers)->match($subject);
    if ($matcher->test()) {
        $refDetail = $matcher->first();
        return true;
    }
    $refDetail = null;
    return false;
}

/**
 * @return Detail[]
 */
function pattern_match_all(string $pattern, string $subject, string $modifiers = ''): array
{
    return Pattern::of($pattern, $modifiers)->match($subject)->all();
}

function pattern_replace(string $pattern, string $subject, string $replacement, string $modifiers = ''): string
{
    return Pattern::of($pattern, $modifiers)->replace($subject)->with($replacement);
}

function pattern_replace_callback(string $pattern, string $subject, callable $callback, string $modifiers = ''): string
{
    return Pattern::of($pattern, $modifiers)->replace($subject)->callback($callback);
}

function pattern_prune(string $pattern, string $subject, string $modifiers = ''): string
{
    return Pattern::of($pattern, $modifiers)->prune($subject);
}

function pattern_count(string $pattern, string $subject, string $modifiers = ''): int
{
    return Pattern::of($pattern, $modifiers)->count($subject);
}

/**
 * @return string[]
 */
function pattern_split(string $pattern, string $subject, string $modifiers = ''): array
{
    return Pattern::of($pattern, $modifiers)->split($subject);
}

/**
 * @return string[]
 */
function pattern_cut(string $pattern, string $subject, string $modifiers = ''): array
{
    return Pattern::of($pattern, $modifiers)->cut($subject);
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

/**
 * @param string $pattern
 * @param string[] $subjects
 * @param string $modifiers
 * @return string[]
 */
function pattern_reject(string $pattern, array $subjects, string $modifiers = ''): array
{
    return Pattern::of($pattern, $modifiers)->reject($subjects);
}
