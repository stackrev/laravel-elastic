<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

use Elasticsearch\ClientBuilder;


class BlogController extends Controller
{
    private $client;
    private $index_name;
    public function __construct(){
        $this->client = ClientBuilder::create()->setElasticCloudId(env('ELASTICSEARCH_COULD_ID'))->setBasicAuthentication(env('ELASTICSEARCH_USERNAME'), env('ELASTICSEARCH_PASSWORD'))->build();
        $this->index_name = 'nations_info_index';

    }
    public function home (Request $request) {
        $q = $request->get('q');
        if ($q) {
            // Build the Elasticsearch client
            // Starting clock time in seconds
            $start_time = microtime(true);

            // Perform a search query
            $params = [
                'index' => $this->index_name,
                'body'  => [
                    'query' => [
                        'multi_match' => [
                            'query' => $q,
                            'fields' => [
                                'title',
                                'content'
                            ]
                        ]
                    ]
                ]
            ];
            $response = $this->client->search($params);
            // End clock time in seconds
            $end_time = microtime(true);
            
            // Calculating the script execution time
            $execution_time = $end_time - $start_time;
            
            echo " searching time = " . $execution_time . " sec";
    
            $posts= array_column($response['hits']['hits'], '_source');
//            dd($posts);
        } else {
            $posts = [];
        }
    
        return view('home', ['posts' => $posts]);
    }
    public function savePost (Request $request) {
    
        // $params = [
        //     'index' => $this->index_name,
        //     'body' => [
        //         'title' => $request->get('title'),
        //         'content' => $request->get('content'),
        //     ],
        // ];

        // $response = $this->client->index($params);

        // if ($response['result'] === 'created') {
        //     // Data indexed successfully
        //     return response()->json(['message' => 'Data indexed successfully']);
        // } else {
        //     // Failed to index data
        //     return response()->json(['message' => 'Failed to index data']);
        // }
        return redirect('/');
    }


}
