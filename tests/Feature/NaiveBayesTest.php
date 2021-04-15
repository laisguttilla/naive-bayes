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
        $comments = BadComments::WhereNotNull('Reclamado')->whereNotNull('RegistroDescricao')->get()->take(30);



        //dump($comments);

        $classifier = new NaiveBayes();
        foreach($comments as $comment) {
            $classifier->learn($comment->Reclamado, $comment->RegistroDescricao);
        }

        // $classifier->learn('achei o aplicativo ótimo, é maravilhoso!', 'positive');
        // $classifier->learn('uma porcaria', 'negative');
        // $classifier->learn('muito bom!', 'positive');

        // $this->assertEquals('positive', $classifier->guess('bom aplicativo'));
        //$this->assertEquals('Portabilidade realizada na linha incorreta', $classifier->guess('tive problemas com a portabilidade ruim'));

        print_r("Classe sugerida -> " . $classifier->guess('tive problemas com a portabilidade ruim' . "\n"));

        print_r($classifier->frequencyPerType('tive problemas com a Portabilidade ruIM'));
        // print_r($classifier->frequencyPerType('horrível'));
    }
}
