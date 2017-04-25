<?php

namespace FuzzyWuzzy;

class Process
{
    /** @var Fuzz */
    private $fuzz;

    /** @var \Closure */
    private $getString;

    /** @var \Closure */
    private $getScore;

    /**
     * @param Fuzz $fuzz
     */
    public function __construct(Fuzz $fuzz = null)
    {
        $this->fuzz = $fuzz ?: new Fuzz();

        $this->getString = function ($x) { return $x[0]; };
        $this->getScore  = function ($x) { return $x[1]; };
    }

    /**
     * Returns a Collection of matches for query, from a list of choices.
     *
     * @param string             $query     Query string.
     * @param array|\Traversable $choices   List of choices to match against.
     * @param \Closure           $processor Processing function (Default: {@link Utils::fullProcess}.
     * @param \Closure           $scorer    Scoring function (Default: {@link Fuzz::weightedRatio}).
     * @param integer            $limit     Limits the number of returned matches (Default: 5).
     * @return Collection
     */
    public function extract($query, $choices = [ ], $processor = null, $scorer = null, $limit = 5)
    {
        $choices = Collection::coerce($choices);

        if ($choices->isEmpty()) { return $choices; }

        $processor = $processor ?: function ($str) { return Utils::fullProcess($str); };
        $scorer    = $scorer    ?: function ($s1, $s2) { return $this->fuzz->weightedRatio($s1, $s2); };

        if (!is_callable($processor)) { throw new \InvalidArgumentException('processor is not callable.'); }
        if (!is_callable($scorer))    { throw new \InvalidArgumentException('scorer is not callable.'); }

        $scored = new Collection();

        foreach ($choices as $choice) {
            $processed = $processor($choice);
            $score     = $scorer($query, $processed);

            $scored->add([$choice, $score]);
        }

        return $scored
            ->multiSort($scored->map($this->getScore)->toArray(), SORT_DESC, SORT_NUMERIC)
            ->slice(0, $limit)
        ;
    }

    /**
     * Returns a Collection of best matches to a collection of choices.
     *
     * @param string             $query     Query string.
     * @param array|\Traversable $choices   List of choices to match against.
     * @param \Closure           $processor Processing function (Default: {@link Utils::fullProcess}.
     * @param \Closure           $scorer    Scoring function (Default: {@link Fuzz::weightedRatio}).
     * @param integer            $cutoff    Score cutoff for returned matches.
     * @param integer            $limit     Limits the number of returned matches (Default: 5).
     * @return Collection
     */
    public function extractBests($query, $choices = [ ], $processor = null, $scorer = null, $cutoff = 0, $limit = 5)
    {
        $bestList = $this->extract($query, $choices, $processor, $scorer, $limit);
        return $bestList->filter(function ($x) use ($cutoff) { return $x[1] >= $cutoff; });
    }

    /**
     * Returns the best match for query from a collection of choices.
     *
     * @param string             $query     Query string.
     * @param array|\Traversable $choices   List of choices to match against.
     * @param \Closure           $processor Processing function (Default: {@link Utils::fullProcess}.
     * @param \Closure           $scorer    Scoring function (Default: {@link Fuzz::weightedRatio}).
     * @param integer            $cutoff    Score cutoff for returned matches.
     * @return array
     */
    public function extractOne($query, $choices = [ ], $processor = null, $scorer = null, $cutoff = 0)
    {
        $bestList = $this->extract($query, $choices, $processor, $scorer, 1);
        return !$bestList->isEmpty() && $bestList[0][1] > $cutoff ? $bestList[0] : null;
    }

    /**
     * Returns a Collection that has been filtered for duplicates using fuzzy matching.
     *
     * @param array|\Traversable $containsDupes List containing duplicate strings.
     * @param integer            $threshold     Match threshold.
     * @param \Closure           $scorer        Scoring function.
     * @return Collection
     */
    public function dedupe($containsDupes = [ ], $threshold = 70, $scorer = null)
    {
        $containsDupes = Collection::coerce($containsDupes);
        $scorer        = $scorer ?: function ($s1, $s2) { return $this->fuzz->tokenSetRatio($s1, $s2); };
        $extractor     = [ ];

        # iterate over containsDupes
        foreach ($containsDupes as $item) {
            # return all duplicate matches found
            $matches  = $this->extract($item, $containsDupes, null, $scorer, null);
            # filter matches based on threshold
            $filtered = $matches->filter(function ($x) use ($threshold) { return $x[1] > $threshold; });
            # if there is only 1 item in *filtered*, no duplicates were found, so append to *extracted*
            if ($filtered->count() === 1) {
                $extractor[] = $filtered[0][0];
            } else {
                # sort length DESC, score DESC, alpha ASC
                $filtered = $filtered->multiSort(
                    $filtered->map(function ($x) { return strlen($x[0]); })->toArray(), SORT_DESC, SORT_NUMERIC,
                    $filtered->map($this->getScore)->toArray(), SORT_DESC, SORT_NUMERIC,
                    $filtered->map($this->getString)->toArray(), SORT_ASC, SORT_STRING | SORT_FLAG_CASE
                );

                $extractor[] = $filtered[0][0];
            }
        }

        # "uniquify" *extractor* list
        $keys = [ ];
        foreach ($extractor as $e) {
            $keys[$e] = 1;
        }

        return count($extractor) === count($containsDupes) ? $containsDupes : $extractor;
    }
}
