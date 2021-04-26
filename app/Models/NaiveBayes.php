<?php

namespace App\Models;

class NaiveBayes
{
    public $types = [];
    public $documents = [];
    public $words = [];
    public $relevantDictionary = [];

    protected $cleaner;

    public function guess($statement)
    {
        $words = $this->getWords($statement);
        $bestLikelihood = 0;
        $bestType = null;

        foreach ($this->types as $type) {
            $likelihood = $this->totalTypeProbability($type);
            foreach ($words as $word) {
                $likelihood = $this->wordPropabilityByType($word, $type);
            }

            if ($likelihood > $bestLikelihood) {
                $bestLikelihood = $likelihood;
                $bestType = $type;
            }
        }

        return $bestType;
    }

    public function learn($statement, $type)
    {
        $words = $this->getWords($statement);
        $this->initializeType($type);

        foreach ($words as $word) {
            if (!isset($this->words[$type][$word])) {
                $this->words[$type][$word] = 0;
            }

            $this->words[$type][$word]++;
        }
        $this->documents[$type]++;
    }

    public function relevantDictionary(array $words, string $type)
    {
        foreach ($words as $word => $value) {
            $this->relevantDictionary[$type][$word] = $value;
        }
    }


    public function frequencyPerType($statement)
    {
        $words = $this->getWords($statement);
        $frequency = [];
        foreach ($words as $word) {
            foreach ($this->types as $type) {
                if (isset($this->words[$type][$word])) {
                    $frequency[$type][$word] = $this->words[$type][$word];
                }
            }
        }

        return $frequency;
    }

    public function initializeType($typeName)
    {
        if (!in_array($typeName, $this->types)) {
            $this->documents[$typeName] = 0;
            array_push($this->types, $typeName);
        }

        return $this;
    }

    public function totalTypeProbability($type)
    {
        return ($this->documents[$type] + 1) / (array_sum($this->documents) + 1);
    }

    public function wordPropabilityByType($word, $type)
    {
        $count = 0;
        if (isset($this->words[$type][$word])) {
            $count = $this->words[$type][$word];
        }

        if (isset($this->relevantDictionary[$type][$word])) {
            $count = $count * $this->relevantDictionary[$type][$word];
        }

        return ($count + 1) / (array_sum($this->words[$type]) + 1);
    }

    public function getWords($statement)
    {
        $formatter = new WordFormatter();
        return $formatter->cleanWords($statement);
    }

}
