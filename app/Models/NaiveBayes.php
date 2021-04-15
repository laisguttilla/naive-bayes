<?php

namespace App\Models;

class NaiveBayes
{
    public $types = [];
    public $documents = [];
    public $words = [];
    public $relevantDictionary = [];

    // protected $cleaner;

    // public function __construct(Cleaner $cleaner)
    // {
    //     $this->cleaner = $cleaner;
    // }

    public function guess($statement)
    {
        $words = $this->getWords($statement);
        $bestLikelihood = 0;
        $bestType = null;
        foreach ($this->types as $type) {
            $likelihood = $this->totalTypeProbability($type);

            foreach ($words as $word) {
                $likelihood *= $this->wordPropabilityByType($word, $type);
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
        $this->relevantDictionary($words, $type);
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
        foreach ($words as $word) {
            if (preg_match('/(["^0-9"])/', $word)) {
                $this->relevantDictionary[$type] = [];
                $relevantWord = preg_replace('/(["^0-9"])/', '', $word);
                $value = (int) preg_filter('([^[0-9])', '', $word);
                $this->relevantDictionary[$type][$relevantWord] =+ $value;
            }
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

                if (isset($this->relevantDictionary[$type][$word])) {
                    $frequency[$type][$word] = $this->relevantDictionary[$type][$word];
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
        if (isset($this->relevantDictionary[$type][$word])) {
            $count = $this->relevantDictionary[$type][$word];
            return ($count + 1) / (array_sum($this->relevantDictionary[$type]) + 1);
        }
        $count = isset($this->words[$type][$word]) ? $this->words[$type][$word] : 0;

        return ($count + 1) / (array_sum($this->words[$type]) + 1);
    }

    public function getWords($statement)
    {
        $formatter = new WordFormatter();
        return $formatter->cleanWords($statement);
    }
}

