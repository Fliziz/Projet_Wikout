<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use DataTimeImmutable; 

class MongoDBService{
    
    public function __construct(HttpClientInterface $httpClient){
        $this->httpClient = $httpClient;
    }

    public function inserVisit(string $pageName){
        $this->httpClient->request('POST', 'https://us-east-2.aws.neurelo.com/rest/visits/__one', [
            'headers' => [
                'X-Api-Key' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImFybjphd3M6a21zOnVzLWVhc3QtMjowMzczODQxMTc5ODQ6YWxpYXMvYjJjYWNlYWItQXV0aC1LZXkifQ.eyJlbnZpcm9ubWVudF9pZCI6ImI0ZDU5M2QxLWVlZjQtNGVlZS04MjgzLWJkNWY1NWViOWNiMyIsImdhdGV3YXlfaWQiOiJnd19iMmNhY2VhYi0yYTRlLTQ3YzYtOTlkZS1iNDM3M2I4NWE2MjIiLCJwb2xpY2llcyI6WyJSRUFEIiwiV1JJVEUiLCJVUERBVEUiLCJERUxFVEUiLCJDVVNUT00iXSwiaWF0IjoiMjAyNS0wMi0yOFQxMzo0NzozNC4wMzYzNzc3MzZaIiwianRpIjoiMGZkNjQ2YTItOWMwYi00M2Y5LTkxYTctMmM5ZGYzM2M4MGYyIn0.cJfzP7We2gQQ6tOsTfaEfMHxkP8N3pnJrlSTaYHghw4zkTrnO-xfl4drzZyfOtXzGVoFwfGofJk5Z2qD-q9awHaIXqLOO3hKYmhGm4gIhhFjMhH-vRZ35-dMj4FHHml6olKCnefTKpqD8BU3Ol0HPT0_jcu0vt_Wv78Kp1CxW_6yYWmrI0KVgEMdh45SxiSzm4l9CtBEHmsfUAcEvZFYsFlMMrpXNgarCDAO2dLLzaWJyarMn2Pw0DiOPcV-rySnVYxluFfD2aeD-I3_6EKrVWxMFshcqF_mTF9qqzR0zP7hnjdx06eTluzd0PPRY84h6qRt7lXSMv3aic29dYZHYA',
                'Content-Type' => 'application/json',
            ],
            
            'json' => [
                'pageName' => $pageName,
                'visitedAt' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}   