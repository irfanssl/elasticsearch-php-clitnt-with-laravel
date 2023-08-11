<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchController extends Controller
{
    private $index = 'books_dataset'; // from table books, the index name is just random name you want, i prefer books_dataset
    private $client;

    public function __construct()
    {
         /*
            ----------- ELASTICSEARCH USING ELASTIC CLIENT BUILDER AND SSL ---------------
            it's required SSL certificate
            the SSL certificate come from elasticsearch installation (location of ssl : elasticsearch/config/certs/http_ca.crt)

            if you use docker just run this command in your vscode git bash:
            
            docker cp es-node01:/usr/share/elasticsearch/config/certs/http_ca.crt storage/app
            note : chenge es-node01 with your elasticsearch container name


            if you aren't use docker , just copy the ssl certificate from elasticsearch/config/certs/http_ca.crt
            and paste it inside folder storage/app
        */
        $this->client = ClientBuilder::create()
                        ->setHosts([env('ELASTICSEARCH_HOST')])
                        ->setBasicAuthentication(env('ELASTICSEARCH_USERNAME'), env('ELASTICSEARCH_PASSWORD'))
                        // if you don't want to use ssl uncomment this line
                        ->setCABundle(storage_path().'/app/es01.crt')
                        // and comment this line
                        // ->setSSLVerification(false)

                        ->build();


        /*
            ----------------- ELASTICSEARCH WITHOUT ELASTIC CLIENT BUILDER AND SSL------------------
            it's not required to use SSL certificate and it using native php curl
            anyway i don't recommend to use El without SSL.

            so don't use it, just comment it for refference
        */ 

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, env('ELASTICSEARCH_HOST'));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($ch, CURLOPT_USERPWD, env('ELASTICSEARCH_USERNAME').':'.env('ELASTICSEARCH_PASSWORD'));
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // $response = curl_exec($ch);

        // curl_close($ch);
        
        // return $response;
    }

    public function isElasticReady(){
        if($this->client->info()->getStatusCode() === 200){
            return true;
        }else{
            return false;
        }
    }


    public function createNewIndex(){
        $params = $params = [
            'index' => $this->index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],

                    // columns : name, description, author (on tbale books)
                    'properties' => [
                        'name' => [
                            'type' => 'keyword'
                        ],
                        'description' => [
                            'type' => 'keyword'
                        ],
                        'author' => [
                            'type' => 'keyword'
                        ]
                    ]
                ]
            ]
        ];
        
        return $this->client->indices()->create($params);
    }


    public function indexingDocument(Int $id, String $jsonString){
        $index = [
            'index' => $this->index,
            'id'    => $id,
            'body'  => $jsonString
        ];
        $response = $this->client->index($index);
        return $response->asArray();
    }

    public function searchDocument(string $keyword){
        $params = [
            'index' => $this->index,
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query' => $keyword,
                        "fields" => ['name','description','author']
                    ]
                ]
            ]
        ];
        
        $response = $this->client->search($params);

        /**
         * this line return error in browser : net::ERR_INCOMPLETE_CHUNKED_ENCODING 200 (OK)
         */
        // return $this->client->search($params); 


        // so, use this line insted
        return $response->withoutHeader('Transfer-Encoding');


        // just for trial and error
        // return $this->client->get([
        //     'id' => 200,
        //     'index' => $this->index
        // ]);
    }
}
