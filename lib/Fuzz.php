<?php

namespace Gowili\FuzzyWuzzy;

class Fuzz
{
    const ENCODING = 'UTF-8';

    /**
     * Fuzz Constructor.
     *
     * @param boolean $setInternalEncoding
     */
    public function __construct($setInternalEncoding = true)
    {
        if ($setInternalEncoding) {
            mb_internal_encoding(self::ENCODING);
        }
    }

    /**
     * Returns a basic ratio score between the two strings.
     *
     * @param string $s1
     * @param string $s2
     *
     * @return integer
     */
    public function ratio($s1, $s2)
    {
        if (empty ($s1)) { throw new \UnexpectedValueException('s1 is empty'); }
        if (empty ($s2)) { throw new \UnexpectedValueException('s2 is empty'); }

        if (mb_strlen($s1) === 0 || mb_strlen($s2) === 0) {
            return 0;
        }

        $m = new Diff\SequenceMatcher($s1, $s2);

        return Utils::intr(100 * $m->Ratio());
    }

    /**
     *
     * @todo Skip duplicate indexes for a little more speed.
     *
     * @param string $s1
     * @param string $s2
     *
     * @return int
     */
    public function partialRatio($s1, $s2)
    {
        if (empty ($s1)) { throw new \UnexpectedValueException('s1 is empty'); }
        if (empty ($s2)) { throw new \UnexpectedValueException('s2 is empty'); }

        if (mb_strlen($s1) === 0 || mb_strlen($s2) === 0) {
            return 0;
        }

        if (mb_strlen($s1) <= mb_strlen($s2)) {
            $shorter = $s1;
            $longer  = $s2;
        } else {
            $shorter = $s2;
            $longer  = $s1;
        }

        $m = new Diff\SequenceMatcher($shorter, $longer);

        $blocks = $m->getMatchingBlocks();
        $scores = [ ];

        foreach ($blocks as $block) {
            $longStart  = $block[1] - $block[0] > 0 ? $block[1] - $block[0] : 0;
            $longEnd    = $longStart + mb_strlen($shorter);
            $longSubstr = mb_substr($longer, $longStart, $longEnd);

            $m2 = new Diff\SequenceMatcher($shorter, $longSubstr);
            $r  = $m2->Ratio();

            if ($r > .995) {
                return 100;
            } else {
                $scores[] = $r;
            }
        }

        return Utils::intr(100 * max($scores));
    }
}
