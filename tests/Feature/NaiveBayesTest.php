<?php

namespace Tests\Feature;

use App\Models\NaiveBayes;
use Tests\TestCase;
use App\Models\LearningBase;
use App\Models\TDNAComment;

class NaiveBayesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNaiveBayes()
    {
        $comments = LearningBase::all();
        $classifier = new NaiveBayes();

        foreach($comments as $comment) {
            $classifier->learn($comment->statement, $comment->type);
        }

        dump(TDNAComment::find('608718a5d4aad01c441e608f')->comment);

        print_r("Classe sugerida -> " . $classifier->guess(TDNAComment::find('608718a5d4aad01c441e608f')->comment));
    }
}
