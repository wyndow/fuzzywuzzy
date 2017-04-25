# FuzzyWuzzy

[![Build Status](https://travis-ci.org/wyndow/fuzzywuzzy.svg?branch=master)](https://travis-ci.org/wyndow/fuzzywuzzy)

Fuzzy string matching for PHP, based on the [python library](https://github.com/seatgeek/fuzzywuzzy) of the same name.

## Requirements

* PHP 5.4 or higher

## Installation

Using [Composer](http://getcomposer.org/)

```
composer require wyndow/fuzzywuzzy
```

## Usage

```php
use FuzzyWuzzy\Fuzz;
use FuzzyWuzzy\Process;

$fuzz = new Fuzz();
$process = new Process($fuzz); // $fuzz is optional here, and can be omitted.
```

### Simple Ratio

```php
>>> $fuzz->ratio('this is a test', 'this is a test!')
=> 96
```

### Partial Ratio

```php
>>> $fuzz->partialRatio('this is a test', 'this is a test!')
=> 100
```

### Token Sort Ratio

```php
>>> $fuzz->ratio('fuzzy wuzzy was a bear', 'wuzzy fuzzy was a bear')
=> 90
>>> $fuzz->tokenSortRatio('fuzzy wuzzy was a bear', 'wuzzy fuzzy was a bear')
=> 100
```

### Token Set Ratio

```php
>>> $fuzz->tokenSortRatio('fuzzy was a bear', 'fuzzy fuzzy was a bear')
=> 84
>>> $fuzz->tokenSetRatio('fuzzy was a bear', 'fuzzy fuzzy was a bear')
=> 100
```

### Process

```php
>>> $choices = ['Atlanta Falcons', 'New York Jets', 'New York Giants', 'Dallas Cowboys']
>>> $c = $process->extract('new york jets', $choices, null, null, 2)
=> FuzzyWuzzy\Collection {#205}
>>> $c->toArray()
=> [
     [
       "New York Jets",
       100,
     ],
     [
       "New York Giants",
       78,
     ],
   ]
>>> $process->extractOne('cowboys', $choices)
=> [
     "Dallas Cowboys",
     90,
   ]
```

You can also pass additional parameters to `extractOne` to make it use a specific scorer.

```php
>>> $process->extractOne('cowbell', $choices, null, [$fuzz, 'ratio'])
=> [
     "Dallas Cowboys",
     38,
   ]
>>> $process->extractOne('cowbell', $choices, null, [$fuzz, 'tokenSetRatio'])
=> [
     "Dallas Cowboys",
     57,
   ]
```

## Caveats

Unicode strings may produce unexpected results. We intend to correct this in future versions.

## Further Reading

* [Fuzzy String Matching in Python](http://chairnerd.seatgeek.com/fuzzywuzzy-fuzzy-string-matching-in-python/)
