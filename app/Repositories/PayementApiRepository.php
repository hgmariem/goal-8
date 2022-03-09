<?php

namespace App\Repositories;

use GuzzleHttp\Client;
// import User Model
use App\Model\Plan;

class PayementApiRepository implements IPayementApiRepository
{
    public function HttpGetAPI(){
        $client = new Client();
    	$response = $client->request('GET', 'https://staging.athenabasketball.com/api/login?email=echedli1@gmail.com&password=099668210');
    	$statusCode = $response->getStatusCode();
    	$body = $response->getBody()->getContents();
         
    }
    
}