<?php

namespace App\Models;

class SentenceFormatter
{
    private $statement = null;

    public function cleanWords($statement)
    {
       $this->statement = $statement;
       $this->toLowerCase()
       ->removeSignals()
       ->chunk();

       return $this->statement;

    }

    public function toLowerCase()
    {
        $this->statement = strtolower($this->statement);
        return $this;
    }

    public function removeSignals()
    {
        $this->statement = preg_replace('/[@?!]/', '' ,$this->statement);
        return $this;
    }

    public function chunk() {
        $this->statement = preg_split('/[.,]+/', $this->statement);

        $output = [];
        foreach ($this->statement as $chunk) {
            array_push($output, ltrim($chunk));
        }
        $this->statement = $output;
        return $this;
    }
}
