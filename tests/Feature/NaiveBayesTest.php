<?php

namespace Tests\Feature;

use App\Models\NaiveBayes;
use Tests\TestCase;
use App\Models\BadComments;

class NaiveBayesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNaiveBayes()
    {
        $comments = BadComments::WhereNotNull('Reclamado')->whereNotNull('RegistroDescricao')->get()->take(50);
        $classifier = new NaiveBayes();

        //$classifier->relevantDictionary();

        foreach($comments as $comment) {
            $classifier->learn($comment->Reclamado, $comment->RegistroDescricao);
        }

        print_r("Classe sugerida -> " . $classifier->guess('minha linha foi cancelada'));

       // print_r($classifier->frequencyPerType());
    }
}
