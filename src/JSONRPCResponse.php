<?php

namespace Kubex\MCP;

class JSONRPCResponse
{
  public string $jsonrpc = '2.0';
  public mixed $id = null;
  public mixed $result = null;
  public ?JSONRPCError $error = null;

  public function toJSON(): string
  {
    $data = ['jsonrpc' => $this->jsonrpc, 'id' => $this->id];
    if($this->error !== null)
    {
      $data['error'] = $this->error->toArray();
    }
    else
    {
      $data['result'] = $this->result;
    }
    return json_encode($data);
  }
}
