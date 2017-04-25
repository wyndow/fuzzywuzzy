<?php

namespace FuzzyWuzzy\Diff;

/**
 * Overloaded SequenceMatcher for constructor convenience.
 *
 * @author Michael Crumm <mike@crumm.net>
 */
class SequenceMatcher extends \Diff_SequenceMatcher
{
    /**
     * SequenceMatcher Constructor.
     *
     * @param array|string $a
     * @param array|string $b
     * @param \Closure|null $junkCallback
     * @param array $options
     */
    public function __construct($a, $b, $junkCallback = null, array $options = [ ])
    {
        parent::__construct($a, $b, $junkCallback, $options);
    }
}
