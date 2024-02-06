<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;

class Auth extends CI_Controller
{
   function get_scheduler()
    {
        // Access Cloud Scheduler API using service account credentials from JSON file and create jwt token for authentication
        $serviceAccountJsonFile = APPPATH . 'third_party/SERVICE_ACCOUNT.json';

        $credentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/cloud-platform',
            $serviceAccountJsonFile,
            [
                'token_uri' => 'https://oauth2.googleapis.com/token',
                'scope' => 'https://www.googleapis.com/auth/cloud-platform'
            ]
        );

        $authToken = $credentials->fetchAuthToken();

        // what to do with the token
        $client = new Client();
        $response = $client->request('GET', 'https://content-cloudscheduler.googleapis.com/v1/projects/PROJECT_ID/locations/LOCATION_ID/jobs', [
            'headers' => [
                'Authorization' => 'Bearer ' . $authToken['access_token']
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        echo json_encode($result);
    }
}
