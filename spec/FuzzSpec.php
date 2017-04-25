<?php

namespace spec\FuzzyWuzzy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FuzzSpec extends ObjectBehavior
{
    static $s1 = "new york mets";
    static $s1a = "new york mets";
    static $s2 = "new YORK mets";
    static $s3 = "the wonderful new york mets";
    static $s4 = "new york mets vs atlanta braves";
    static $s5 = "atlanta braves vs new york mets";
    static $s6 = "new york mets - atlanta braves";
    static $s7 = 'new york city mets - atlanta braves';

    static $cirque_strings = [
        "cirque du soleil - zarkana - las vegas",
        "cirque du soleil ",
        "cirque du soleil las vegas",
        "zarkana las vegas",
        "las vegas cirque du soleil at the bellagio",
        "zarakana - cirque du soleil - bellagio"
    ];

    static $baseball_strings = [
        "new york mets vs chicago cubs",
        "chicago cubs vs chicago white sox",
        "philladelphia phillies vs atlanta braves",
        "braves vs mets",
    ];

    function it_is_initializable()
    {
        $this->shouldHaveType('FuzzyWuzzy\Fuzz');
    }

    function it_returns_a_perfect_match_for_ratio()
    {
        $this->ratio(self::$s1, self::$s1a)->shouldBe(100);
    }

    function it_is_case_sensitive_for_ratio()
    {
        $this->ratio(self::$s1, self::$s2)->shouldNotBe(100);
    }

    function it_returns_a_perfect_match_for_partial_ratio()
    {
        $this->partialRatio(self::$s1, self::$s3)->shouldBe(100);
    }
}
