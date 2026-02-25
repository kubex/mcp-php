<?php

namespace Kubex\MCP;

class JSONRPCRequest
{
  public string $jsonrpc = '2.0';
  public mixed $id = null;
  public string $method = '';
  public mixed $params = null;

  public static function fromJSON(string $json): ?self
  {
    $data = json_decode($json, true);
    if(!is_array($data))
    {
      return null;
    }
    $req = new self();
    $req->jsonrpc = $data['jsonrpc'] ?? '';
    $req->id = $data['id'] ?? null;
    $req->method = $data['method'] ?? '';
    $req->params = $data['params'] ?? null;
    return $req;
  }
}
