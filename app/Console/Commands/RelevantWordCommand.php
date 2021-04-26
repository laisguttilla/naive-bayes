<?php

namespace App\Console\Commands;

use App\Models\RelevantWord;
use DirectoryIterator;
use Illuminate\Console\Command;

class RelevantWordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongo:relevant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa o CVSs que estejam na pasta imports/relevant para o Mongo';

    private $relevantWord;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(RelevantWord $relevantWord)
    {
        $this->relevantWord = $relevantWord;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dir = base_path() . '/imports/relevant';
        $file = new DirectoryIterator($dir);
        $fileCount = count(scandir($dir));
        $filesRead = 0;

        while ($filesRead < $fileCount) {
            if ($file->getExtension() == 'csv') {
                $this->import($file);
                unlink($file->getPathname());
            }
            $file->next();
            $filesRead += 1;
            continue;
        }

        $this->info('Importação concluída com sucesso');
    }

    private function import($file)
    {
        $csv = fopen($file->getPathname(), 'r');
        foreach (fgetcsv($csv, 0, ';') as $index => $type) {
            while (($line = fgetcsv($csv, 0, ';')) !== false) {
                $params = [];
                $data =  $this->prepareDataToImport($index, $type, $line, $params);
                $this->relevantWord->create($data);
                $this->info("Documento inserido com sucesso");
            }
        }

        $this->info("Arquivo: {$file->getFileName()} importado com sucesso");
    }

    private function prepareDataToImport($index, $type, $line, $data)
    {
        $line[$index] != "" && $line[$index] != "\x00" && $line[$index] ? $data["type"] = $type : '';
        $line[$index] != "" && $line[$index] != "\x00" && $line[$index] ? $data["statement"] = $line[$index] : '';

        return $data;
    }
}
