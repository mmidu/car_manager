<?php


namespace App\Services;
use App\Interfaces\LedgerServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;


class LedgerService implements LedgerServiceInterface
{
    protected $client;
    public $domain;

    public function __construct()
    {
        $this->client = new Client();
        $ledger = Config::get('database.ledger');
        $this->domain = 'http://'.$ledger['host'].':'.$ledger['port'].'/';
    }

    public function version():object{
        try{
            return $this->response(true, $this->client->get($this->domain.'api')->getBody());
        } catch(ClientException $exception){
            return $this->response(false, $exception->getMessage());
        }
    }

    public function getCar(string $plate):object{
        try{
            return $this->response(true, $this->client->get($this->domain.'car/'.$plate)->getBody());
        } catch(ClientException $exception){
            return $this->response(false, $exception->getMessage());
        }
    }

    private function response($status, $message): object{
        return (object)[
          "status" => $status,
          "data" => $message
        ];
    }
}
