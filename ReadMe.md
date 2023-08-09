<p align="center">
    <a href="https://t-regx.com"><img src=".github/assets/t.regx.png" alt="T-Regx"></a>
</p>
<p align="center">
    <a href="https://github.com/t-regx/pattern-functions/actions/"><img src="https://github.com/t-regx/pattern-functions/workflows/build/badge.svg" alt="Build status"/></a>
    <a href="https://coveralls.io/github/t-regx/pattern-functions"><img src="https://coveralls.io/repos/github/t-regx/pattern-functions/badge.svg" alt="Coverage"/></a>
    <a href="https://github.com/t-regx/pattern-functions/releases"><img src="https://img.shields.io/badge/alpha-0.1.0-brightgreen.svg?style=popout" alt="alpha: 0.1.0"/></a>
</p>

# T-Regx | Set of function helpers

`pattern/functions` is a set of helper functions, simplifying the usage of [`Pattern`](https://github.com/T-Regx/T-Regx)
of standard [T-Regx](https://github.com/T-Regx/T-Regx) library.

[See documentation](https://t-regx.com/) at [t-regx.com](https://t-regx.com/).

[![Code Climate](https://img.shields.io/codeclimate/maintainability/t-regx/pattern-functions.svg)](https://codeclimate.com/github/t-regx/pattern-functions)
[![PRs Welcome](https://img.shields.io/badge/PR-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)
[![Gitter](https://badges.gitter.im/T-Regx/community.svg)](https://gitter.im/T-Regx/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

[![OS Arch](https://img.shields.io/badge/OS-32&hyphen;bit-brightgreen.svg)](https://github.com/t-regx/pattern-functions/actions)
[![OS Arch](https://img.shields.io/badge/OS-64&hyphen;bit-brightgreen.svg)](https://github.com/t-regx/pattern-functions/actions)
[![OS Arch](https://img.shields.io/badge/OS-Windows-blue.svg)](https://github.com/t-regx/pattern-functions/actions)
[![OS Arch](https://img.shields.io/badge/OS-Linux/Unix-blue.svg)](https://github.com/t-regx/pattern-functions/actions)

[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://github.com/t-regx/pattern-functions/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://github.com/t-regx/pattern-functions/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://github.com/t-regx/pattern-functions/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://github.com/t-regx/pattern-functions/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-blue.svg)](https://github.com/t-regx/pattern-functions/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1-blue.svg)](https://github.com/t-regx/pattern-functions/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://github.com/t-regx/pattern-functions/actions)

1. [Installation](#installation)
   - [Composer](#installation)
2. [Overview](#overview)
3. [Documentation](#documentation)
   1. [Examples](#examples)
      - `pattern_test()`
      - `pattern_fails()`
      - `pattern_count()`
      - `pattern_match_first()`
      - `pattern_match_optional()`
      - `pattern_match_ref()`
      - `pattern_match_all()`
      - `pattern_replace()`
      - `pattern_replace_callback()`
      - `pattern_split()`
      - `pattern_cut()`
      - `pattern_filter()`
      - `pattern_reject()`
      - `pattern_search()`
4. [API reference](#api-reference)
   1. [Exception reference](#exception-reference)
   2. [Identities](#identities)
5. [Frequently asked questions](#frequently-asked-questions)
6. [Comparison against `preg_` functions](#comparison-against-preg-functions)
7. [Sponsors](#sponsors)
8. [License](#license)

[Buy me a coffee!](https://www.buymeacoffee.com/danielwilkowski)

# Installation

Installation for PHP 7.1-8.2 and later:

Package `pattern/functions` is an addon to `rawr/t-regx`.

```bash
composer require pattern/functions
```

Installing `pattern/functions` will also install [core] `rawr/t-regx`.

Currently, T-Regx composer package is named `rawr/t-regx`. In the future, with release 1.0 the T-Regx package will be
renamed to `pattern/pattern`.

# Overview

[T-Regx library](https://github.com/t-regx/T-Regx) is an object-oriented approach to regular expressions, with
classes `Pattern`, `Matcher` etc. Find more in ["Introduction to T-Regx"](https://t-regx.com/docs/introduction).

On the other hand, a simplified approach is possible with helper functions in `pattern/helpers` package. It adds
simplified functions for an easier start with the library.

Examples with helper functions:

```php
<?php
if (pattern_test('\w+', $_GET['subject'])) {
  $matches = pattern_match_all('\w+', $_GET['subject']);
  foreach ($matcher as $match) {
      echo "Found a match $match at position {$match->offset()}!";
  }
  echo "Occurrences found: " . pattern_count('\w+', $_GET['subject']);
} 
else {
  echo "No matches found :/";
}
return pattern_split('\w+', $_GET['subject']);
```

```php
<?php
return pattern_replace('\w+', $_GET['subject'], '***');
```

```php
<?php
return pattern_replace_callback('\w+', $_GET['subject'], fn (Detail $match) => '***');
```

```php
<?php
try {
   $match = pattern_match_first('\w+', $_GET['subject'], fn (Detail $match) => '***');
}
catch (MalformedPatternException $exception) {
   // used improper regular expression
}
catch (CatastrophicBacktrackingException $exception) {
   // catastrophic backtracking while matching
}
```

Helper functions utilize the same implementation as `Pattern` in the core library.

# Documentation

Available functions:

- Predication:
   - `pattern_test()` - returns `true`, if pattern matches the subject
   - `pattern_count()` - returns number of occurrences of pattern in the subject
- Retrieve match/matches:
   - `pattern_match_first()` - returns the first matched occurrence, as [`Detail`]
   - `pattern_match_optional()` - returns an optional matched occurrence,
     as <code><a href="https://t-regx.com/docs/match-details">Detail</a>|null</code>
   - `pattern_match_ref()` - populates `&Detail` via ref-argument and returns `true`/`false`
   - `pattern_match_all()` - returns all matched occurrences, as `Detail[]`
- Replacing:
   - `pattern_replace()` - replaces occurrences of pattern in the subject with given string
   - `pattern_replace_callback()` - replaces occurrences of pattern in the subject via given callback
   - `pattern_prune()` - removes occurrences of pattern from the subject
- Splitting:
   - `pattern_split()` - returns `string[]` separated by occurrences of the pattern in the subject
   - `pattern_cut()` - returns a two-element array separated by a single occurrence of pattern in the subject
- Filtering array
   - `pattern_filter()` - accepts `string[]` and returns `string[]` with only subjects matching the pattern
   - `pattern_reject()` - accepts `string[]` and returns `string[]` with subjects that don't match the pattern

Scroll to ["API Reference"](#api-reference).

Scroll to ["Exception Reference"](#exception-reference).

Scroll to ["Frequently asked questions"](#frequently-asked-questions).

## Examples

- Check whether the pattern matches the subject:
  ```php
  if (pattern_test('[A-Z][a-z]+', $_GET['username']) === true) {
  }
  ```
  Function `pattern_test()` is another notation of
  ```php
  $pattern = Pattern::of('[A-Z][a-z]+');
  if ($pattern->test($_GET['username'])) {
  }
  ```
  Examples of error handling
  ```php
  try {
    $matched = pattern_test($pattern, $subject);
  } catch (MalformedPatternException $exception) {
  } catch (CatastrophicBacktrackingException $exception) {
  } catch (SubjectEncodingException $exception) {
  } catch (RecursionException $exception) {
  }
  ```
  T-Regx library is based on exceptions, and so are helper functions. Function `pattern_test()`
  doesn't issue any warnings, notices and doesn't set any C-*like* status codes.

- Retrieve the first occurrence of pattern in the subject:
  ```php
  /**
   * @var Detail $match
   */
  $match = pattern_match_first('\w+', 'Welcome to the jungle');
  
  $match->text();
  $match->offset();
  $match->group(2);
  $match->toInt();
  ```

  Function `pattern_match_first()` is another notation of
  ```php
  $pattern = Pattern::of('[A-Z][a-z]+');
  $matcher = $pattern->match($_GET['username']);
  /**
   * @var Detail $match
   */
  $match = $matcher->first();
  ```

- Retrieve the matched occurrence of pattern in a subject, or `null` for an unmatched subject:
  ```php
  /**
   * @var ?Detail $match
   */
  $match = pattern_match_optional('\w+', 'Welcome to the jungle');
  
  if ($match === null) {
    // pattern not matched
  } else {
    $match->text();
    $match->offset();
    $match->group(2);
    $match->toInt();
  }
  ```
  More about [`Detail`](https://t-regx.com/docs/match-details) can be found
  in ["Match details"](https://t-regx.com/docs/match-details).

- Read matched occurrence details of pattern in a subject, and populate `Detail` as a reference argument:
  ```php
  if (pattern_match_ref('\w+', 'Welcome to the jungle', $match)) {
    /**
     * @var Detail $match
     */
    $match->text();
    $match->offset();
    $match->group(2);
    $match->toInt();
  } else {
    // pattern not matched
  }
  ```
  More about [`Detail`](https://t-regx.com/docs/match-details) can be found
  in ["Match details"](https://t-regx.com/docs/match-details).

- Retrieve all matched occurrences of pattern in the subject:
  ```php
  /**
   * @var Detail[] $matches
   */
  $matches = pattern_match_all('\w+', 'Winter is coming');
  foreach ($matches as $match) {
  }
  ```

  Function `pattern_match_all()` is another notation of
  ```php
  $pattern = Pattern::of('\w+');
  $matcher = $pattern->match('Winter is coming');
  $matches = $matcher->all();
  foreach ($matches as $match) {
  }
  ```

- Replace occurrences of pattern in the subject with a given `string` replacement or `callable`:
  ```php
  $slug = pattern_replace('\s+', 'We do not sow', '-');
  ```
  ```php
  $slug = pattern_replace_callback('\s+', 'We do not sow', fn (Detail $match) => '-');
  ```

  Functions `pattern_replace()` and `pattern_replace_callback()` are another notation of
  ```php
  $pattern = Pattern::of('\s+');
  $replace = $pattern->replace('We do not sow');
  
  $slug = $replace->with('-');
  $slug = $replace->callback(fn (Detail $match) => '-');
  ```

More about [`Detail`](https://t-regx.com/docs/match-details) can be found
in ["Match details"](https://t-regx.com/docs/match-details).

# API Reference

Predication

- `pattern_test()` returns `true` if `$pattern` matches the `$subject`, `false` otherwise.
  ```php
  pattern_test(string $pattern, string $subject, string $modifiers=''): bool;
  ```
- `pattern_fails()` returns `false` if `$pattern` matches the `$subject`, `true` otherwise.
  ```php
  pattern_fails(string $pattern, string $subject, string $modifiers=''): bool;
  ```
- `pattern_count()` returns the number of occurrences of `$pattern` in `$subject`.
  ```php
  pattern_count(string $pattern, string $subject, string $modifiers=''): int;
  ```

Matching

- `pattern_match_first()` returns the first occurrence of pattern in the subject as `Detail`;
  throws `SubjectNotMatchedException` if the pattern doesn't match the subject.
  ```php
  pattern_match_first(string $pattern, string $subject, string $modifiers=''): Detail;
  ```
- `pattern_match_optional()` returns the first occurrence of pattern in the subject as `Detail`; returns `null` if the
  pattern doesn't match the subject.
  ```php
  pattern_match_optional(string $pattern, string $subject, string $modifiers=''): Detail|null;
  ```
- `pattern_match_ref()` returns `true` and populates `&Detail` as ref-argument with the first occurrence of pattern in
  the subject; returns `false` if the pattern doesn't match the subject.
  ```php
  pattern_match_ref(string $pattern, string $subject, ?Detail &$refDetail, string $modifiers=''): bool;
  ```
- `pattern_match_all()` returns all occurrences of pattern in the subject as `Detail[]`; returns an empty array if the
  pattern doesn't match the subject.
  ```php
  pattern_match_all(string $pattern, string $subject, string $modifiers=''): Detail[];
  ```
- `pattern_search()` returns all occurrences of pattern in the subject as `string[]`; returns an empty array if the
  pattern doesn't match the subject.
  ```php
  pattern_search(string $pattern, string $subject, string $modifiers=''): array;
  ```

Replacing

- `pattern_replace()` replaces all occurrences of pattern in a subject with a given replacement.
  ```php
  pattern_replace(string $pattern, string $subject, string $replacement, string $modifiers=''): string;
  ```
- `pattern_replace_callback()` replaces all occurrences of pattern in a subject via the specified callback. The callback
  is passed `Detail` as the only argument, and only accepts `string` as return type.
  ```php
  pattern_replace_callback(string $pattern, string $subject, callable $callback, string $modifiers=''): string;
  ```
- `pattern_prune()` removes all occurrences of pattern from a subject.
  ```php
  pattern_prune(string $pattern, string $subject, string $modifiers=''): string;
  ```

Splitting

- `pattern_split()` separates the subject by occurrences of pattern in the subject. If two occurrences of pattern are
  found in the subject next to each other, then an empty string is present in the returned array to indicate it. A part
  or whole separator can be included in the returned array by adding a capturing group in the pattern.
  ```php
  pattern_split(string $pattern, string $subject, string $modifiers=''): string[];
  ```
- `pattern_cut()` splits a subject into exactly two elements, which are returned as `string[]`. The separating pattern
  occurrence is not included in the result, `pattern_cut()` only returns a two-element array. If there are more
  occurrences of the pattern in the subject, or subject is not matched at all, then `UnevenCutException`
  is thrown.
  ```php
  pattern_cut(string $pattern, string $subject, string $modifiers=''): string[];
  ```

Filtering subject lists

- `pattern_filter()` accepts `string[]` of subjects, and returns `string[]` which only contains subjects which match the
  pattern.
  ```php
  pattern_filter(string $pattern, string[] $subjects, string $modifiers=''): string[];
  ```
- `pattern_reject()` accepts `string[]` of subjects, and returns `string[]` which only contains subjects which do not
  match the pattern.
  ```php
  pattern_reject(string $pattern, string[] $subjects, string $modifiers=''): string[];
  ```

Helper for `Pattern`

- `pattern()` is an alias for `Pattern::of()`.
  ```php
  pattern(string $pattern, string $modifiers=''): Pattern;
  ```

## Exception reference

All of the functions in `pattern/functions` library throw the specified exceptions:

- `MalformedPatternException` - when pattern is given not conforming to the regular expression syntax
- `RecursionException` - when recursion limit was exhausted while matching the subject
- `CatastrophicBacktrackingException` - when backtracking limit was exhausted while matching the subject
- `SubjectEncodingException` - when improperly encoded subject is used in unicode-mode
- `JitStackLimitException` - when JIT-compilation optimisation could not be added, due to exhausted limit

## Identities

Functions in `pattern/functions` are thin wrappers around functionalities in [core] library.

- `pattern()` - identical to `Pattern::of()`
- `pattern_test()` - identical to `Pattern.test()`
- `pattern_fails()` - identical to `Pattern.fails()`
- `pattern_match_first()` - identical to `Matcher.first()`
- `pattern_match_all()` - identical to `Matcher.all()`
- `pattern_search()` - identical to `Pattern.search()`
- `pattern_replace()` - identical to `Replace.with()`
- `pattern_replace_callback()` - identical to `Replace.callback()`
- `pattern_prune()` - identical to `Pattern.prune()`
- `pattern_count()` - identical to `Pattern.count()`
- `pattern_split()` - identical to `Pattern.split()`
- `pattern_cut()` - identical to `Pattern.cut()`
- `pattern_filter()` - identical to `Pattern.filter()`
- `pattern_reject()` - identical to `Pattern.reject()`

Functions which aren't directly present in [core] library, but can be easily simulated.

- `pattern_match_optional()` - checks `Matcher.test()`, then returns either `Matcher.first()` or `null`
- `pattern_match_ref()` - checks `Matcher.test()`, then passes `&$ref` with `Matcher.first()` or `null`

# Frequently asked questions

- Why is `pattern/functions` a separate library, and not a part of [core] library

   - Most object-oriented projects develop their applications only using classes, which are not "polluted" by rouge
     functions in the global namespace. To add global functions to the project is not in our competence.

     However, some users actually prefer the simplified approach, and they should be able to easily start their project.
     Because of that `pattern/functions` is available.

- Why functions `pattern_replace()` and `pattern_replace_callback()` are separate functions, instead of a single
  function with dynamic type check `string` and `callable`?

   - We decided to separate the functions, because certain PHP `string` are also `callable`
     (e.g. `'strlen'`, `'strtoupper'`, etc.).

- Why isn't there `pattern_quote()`?

   - Using `preg_quote()` is a more procedural approach to building regular expressions. In T-Regx, the recommended
     approach is using [Prepared patterns]: `Pattern::inject()`
     and `Pattern::template()`. Additionally, `preg_quote()` doesn't quote all of the necessary characters, for example
     in comments and also whitespaces in `/x` mode.

- Why I shouldn't use `@` with `pattern/functions`?

   - Notation `@` is supposed to suppress PHP warnings/errors/notices, but T-Regx library doesn't issue warnings, errors
     and notices, so `@` is redundant. Both [core] library and `pattern/functions` only throw exceptions,
     so `try`/`catch` should be used.

- Why isn't there `pattern_last_error()`?

   - Such function is not necessary, since all functions in `pattern/functions` as well as in
     [core] library throw suitable exceptions on error.

- Should I choose [core] T-Regx library or `pattern/functions`?

   - Choose the interface works better for your project. Everything that can be done with
     `pattern/functions` can also be done with [core] library, and `pattern/functions` are really thin wrappers on
     the [core] library.

- Can I do with `pattern/functions` everything I can do with [core] library?

   - Actually, [core] library is much more powerful than just `pattern/functions`. Most notably
     [core] library offers [Prepared patterns] and [`Pattern::list()`], among other functionalities.

- How does performance `pattern/functions` relate to [core] library?

   - In case of a single call (for example comparing `pattern_test()` and `Pattern.test()`) there is no difference in
     performance.
   - In case of repeated calls with the same pattern, then the [core] library approach is *technically*
     more performant, because of reused compiled pattern; however the difference can only be shown with millions of
     repeated matches.

- How does T-Regx library prevent fatal errors?

   - Certain input values cause PHP and PCRE to fatally end and terminate the PHP process (for example, returning a
     non-stringable object from `preg_replace_callback()` terminates the application). T-Regx handles that by utilizing
     a careful `if`-ology, and when potentially dangerous value is returned, an exception is thrown instead.

# Comparison against `preg_` functions

- `pattern_` functions accept an undelimited regular expression, while `preg_` functions argument must be delimited.
- `pattern_test()` returns `true`/`false`, while `preg_match()` returns `0`/`1`.
- `pattern_` functions handle errors with exceptions, while `preg_` functions use warnings, notices, errors, fatal
  errors and error codes with `preg_last_error()`.
- `pattern_` functions exposes details as `Detail` interface, while `preg_` returns nested arrays.
- `pattern_match_all()` returns an array of [`Detail`] objects, while `preg_match_all()` populates `(string|int)[][]`
  via `&$ref`.

# Sponsors

- [Andreas Leathley](https://github.com/iquito) - developing [SquirrelPHP](https://github.com/squirrelphp)
- [BarxizePL](https://github.com/BarxizePL) - Thanks!

## T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## Our other tools

- `phpunit-data-provider`

## License

T-Regx is [MIT licensed](LICENSE).

[core]: https://github.com/t-regx/T-Regx

[`Detail`]: https://t-regx.com/docs/match-details

[Prepared patterns]: https://t-regx.com/docs/prepared-patterns

[`Pattern::list()`]: https://t-regx.com/docs/pattern-list
