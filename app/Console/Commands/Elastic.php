<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Book;
use App\Http\Controllers\ElasticsearchController;
use Illuminate\Support\Facades\DB;

class Elastic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $book = Book::select('id','name','description','author')->get();

        // $elastic = new ElasticsearchController();
        // $elastic->createNewIndex();
        
        // // only 1000 records for now
        // for($i = 0; $i < 1000; $i++){
        //     $elastic->indexingDocument($book[$i]->id, json_encode($book[$i]));
        //     echo 'id : '.$i.' imported';
        //     echo "\n";
        // }

        // chunk per 1000 to minimize memory usage
        $elastic = new ElasticsearchController();
        $elastic->createNewIndex();
        DB::table('books')
            ->select('id','name','description','author')
            ->orderBy('id')
            ->chunk(1000, function ($books) use($elastic){
                foreach ($books as $book) {
                     $elastic->indexingDocument($book->id, json_encode($book));
                     echo 'id : '.$book->id.' imported'; // just print to console to see the progress
                     echo "\n";
                }
        });
        
        return Command::SUCCESS;
    }
}
