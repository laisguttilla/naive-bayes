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
        $comments = BadComments::WhereNotNull('Reclamado')->whereNotNull('RegistroDescricao')->get()->take(100);

        $classifier = new NaiveBayes();

        $classifier->relevantDictionary(['portabilidade' => 3000], 'Portabilidade realizada na linha incorreta');

        foreach($comments as $comment) {
            $classifier->learn($comment->Reclamado, $comment->RegistroDescricao);
        }

        print_r("Classe sugerida -> " . $classifier->guess('tive problemas com a portabilidade ruim' . "\n"));

         print_r($classifier->frequencyPerType('tive problemas com a portabilidade ruim'));
    }
}
