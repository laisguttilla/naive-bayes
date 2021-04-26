<?php

namespace App\Console\Commands;

use App\Models\LearningBase;
use DirectoryIterator;
use Illuminate\Console\Command;

class LearningBaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongo:learn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa o CVSs que estejam na pasta imports/learn para o Mongo';

    private $learningBase;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LearningBase $learningBase)
    {
        $this->learningBase = $learningBase;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dir = base_path() . '/imports/learn';
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
        while (($line = fgetcsv($csv, 0, ';')) !== false) {
            $params = [];

            $data =  $this->prepareDataToImport($line, $params);

            if (count($data)) {
                $this->learningBase->create($data);
                $this->info("Documento inserido com sucesso");
            }
        }

        $this->info("Arquivo: {$file->getFileName()} importado com sucesso");
    }

    private function prepareDataToImport($line, $data)
    {
        $line[16] != "" && $line[16] != "\x00" && $line[16] != 'SUBTÓPICO 1' ? $data["type"] = $line[16] : null;
        $line[8] != "" && $line[8] != "\x00" && isset($data["type"]) && $line[16] != 'ABERTA' ? $data["statement"] = $line[8] : null;

        return $data;
    }
}
