<?php
namespace Test\Fixtures;

/**
 * This pattern and subject are deliberately created to
 * produce {@see CatastrophicBacktrackingException}, if they
 * are called more than once. That way, we can test
 * whether "first" method really tries to search the first
 * occurrence.
 */
trait CausesBacktracking
{
    public function backtrackingPattern(): string
    {
        return '(\d+\d+)+3';
    }

    public function backtrackingSubject(int $index): string
    {
        $simpleMatches = str_repeat('123 ', $index);
        $hardMatch = '11111111111111111111';
        return "€, $simpleMatches, $hardMatch 3";
    }
}
