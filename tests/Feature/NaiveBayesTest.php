<?php

namespace Tests\Feature;

use App\Models\NaiveBayes;
use Tests\TestCase;

class NaiveBayesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNaiveBayes()
    {
        $classifier = new NaiveBayes();
        $classifier->learn('achei o APLICATIVO muito           horivel^3 !!!', 'negative');
        // $classifier->learn('achei o aplicativo ótimo, é maravilhoso!', 'positive');
        // $classifier->learn('uma porcaria', 'negative');
        // $classifier->learn('muito bom!', 'positive');

        // $this->assertEquals('positive', $classifier->guess('bom aplicativo'));
        $this->assertEquals('negative', $classifier->guess('é muito horrível!'));

        // print_r($classifier->frequencyPerType('esse aplicativo é maravilhoso'));
        // print_r($classifier->frequencyPerType('horrível'));
    }
}
