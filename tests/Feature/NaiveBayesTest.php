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
        $classifier->relevantDictionary([
            'horrível' => 50000
        ], 'negative');
        $classifier->learn('achei o APLICATIVO muito           horrível!!!', 'negative');
        $classifier->learn('é uma droga', 'negative');
        $classifier->learn('achei horrível!', 'super negative a parada');
        $classifier->learn('achei horrível pra cacete!', 'super negative a parada');
        // $classifier->learn('achei o aplicativo ótimo, é maravilhoso!', 'positive');
        // $classifier->learn('uma porcaria', 'negative');
        // $classifier->learn('muito bom!', 'positive');

        // $this->assertEquals('positive', $classifier->guess('bom aplicativo'));
        $this->assertEquals('super negative a parada', $classifier->guess('é um cacete!'));

        // print_r($classifier->frequencyPerType('esse aplicativo é maravilhoso'));
        // print_r($classifier->frequencyPerType('horrível'));
    }
}
