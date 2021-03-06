<?php
namespace Intercom;

use Intercom\IntercomClient;
use GuzzleHttp\Exception\ClientException;
use function GuzzleHttp\Psr7\stream_for;

class Api {

  /** @var Client $client */
  public $client; 

  function __construct($appId=false,$apiKey=false)
  {
    $this->client = new IntercomClient($appId,$apiKey);
  }

  public function parseExceptionError(ClientException $e)
  {
    if($e->hasResponse())
    {
      $stream = stream_for($e->getResponse()->getBody());
      return json_decode($stream->getContents());
    }
    else
    {
      $res = new stdClass;
      $res->type = "error.list";
      $res->request_id = "";
      $errors = new stdClass;
      $errors->code = "timed_out";
      $errors->message = "no response found";
      $res->errors = [$errors];
      return $res;
    }
  }
}
