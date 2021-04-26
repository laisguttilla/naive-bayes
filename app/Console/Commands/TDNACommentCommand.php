<?php

namespace App\Console\Commands;

use App\Models\TDNAComment;
use DirectoryIterator;
use Illuminate\Console\Command;

class TDNACommentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongo:tdna';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa o CVSs que estejam na pasta imports/tdna para o Mongo';

    private $tdnaComment;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TDNAComment $tdnaComment)
    {
        $this->tdnaComment = $tdnaComment;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dir = base_path() . '/imports/tdna';
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

            $this->tdnaComment->create($data);
            $this->info("Documento inserido com sucesso");
        }

        $this->info("Arquivo: {$file->getFileName()} importado com sucesso");
    }

    private function prepareDataToImport($line, $data)
    {
        $line[8] != "" && $line[8] != "\x00" ? $data["grade"] = $line[8] : '';
        $line[4] != "" && $line[4] != "\x00" ? $data["answer_id"] = $line[4] : '';
        $line[39] != "" && $line[39] != "\x00" ? $data["la"] = $line[39] : '';
        $line[17] != "" && $line[17] != "\x00" ? $data["comment"] = $line[17] : '';

        return $data;
    }
}
