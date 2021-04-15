<?php

namespace App\Models;

class WordFormatter
{
    private $stopWords = [
        'a', 'aplicativo', 'ainda', 'achei', 'além', 'ambas', 'ambos', 'ao', 'as', 'da', 'de', 'do', 'seu', 'que', 'a', 'o', 'e', 'é', 'em', 'essa', 'esse', 'uma', 'um', 'uma', 'uns'
    ];

    private $dictionary = [
        'horrível'
    ];

    private $statement = null;

    public function cleanWords($statement)
    {
       $this->statement = $statement;
       $this->toLowerCase()
       ->removeSignals()
       ->tokenize()
       ->cleanStopWords()
       ->applyLevenshtein();

       return $this->statement;

    }

    public function toLowerCase()
    {
        $this->statement = strtolower($this->statement);
        return $this;
    }

    public function tokenize()
    {
        $this->statement = preg_split("/[\s,]+/", $this->statement);
        return $this;
    }

    public function removeSignals()
    {
        $this->statement = preg_replace('/[@?!,.]/', '' ,$this->statement);
        return $this;
    }

    public function cleanStopWords()
    {
        $this->statement = $this->statement = array_diff($this->statement, $this->stopWords);
        return $this;
    }

    public function applyLevenshtein()
    {
        $distanceLimit = 1;
        foreach ($this->statement as $word) {
            $word =  preg_replace('/(["^0-9"])/', '', $word);
            foreach ($this->dictionary as $correctWord) {
                if (preg_match('/([àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ])/', $word)) {
                    $distanceLimit = 2;
                }
                $distance = levenshtein($word, $correctWord);
                $word = $distance > $distanceLimit ? $word : $correctWord;
            }
        }
    }
}
