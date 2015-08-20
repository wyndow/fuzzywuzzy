<?php

namespace Gowili\FuzzyWuzzy\Diff;

class SequenceMatcher extends \Diff_SequenceMatcher
{
    /**
     * @param array|string $a
     * @param array|string $b
     * @param null $junkCallback
     * @param array $options
     */
    public function __construct($a, $b, $junkCallback = null, array $options = [ ])
    {
        parent::__construct($a, $b, $junkCallback, $options);
    }
}
