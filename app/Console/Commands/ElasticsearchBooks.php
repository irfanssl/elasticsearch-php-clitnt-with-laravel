<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ElasticsearchController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ElasticsearchBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:import-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import books table to elasticsearch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $elastic = new ElasticsearchController('books_dataset');
        $response = $elastic->createNewIndex(['name', 'description', 'author']); // columns : name, description, author (on tbale books)
    
        if($response->getStatusCode() === 200){
            DB::table('books')
                ->select('id','name','description','author')
                ->orderBy('id')
                ->chunk(1000, function ($books) use($elastic){ // chunk per 1000 to minimize memory usage
                    foreach ($books as $book) {
                         $response = $elastic->indexingDocument($book->id, json_encode($book));
                         if($response->getStatusCode() === 200){
                             echo 'id : '.$book->id.' imported'; // just print to console to see the progress
                             echo "\n";
                         }else{
                            Log::error('command '.$this->description.' has some troble', [
                                'message' => $response 
                            ]);
                            return Command::FAILURE;
                            exit;
                         }
                    }
            });

            Log::info('command '.$this->description.' success at '.now());
            return Command::SUCCESS;
        }else{
            Log::error('command '.$this->description.' fail at '.now(), [
                'message' => $response
            ]);
            return Command::FAILURE;
        }
        
    }
}
