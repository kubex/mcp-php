<?php

namespace Kubex\MCP;

class JSONRPCError
{
  public function __construct(
    public int $code,
    public string $message,
    public mixed $data = null,
  ) {}

  public function toArray(): array
  {
    $arr = ['code' => $this->code, 'message' => $this->message];
    if($this->data !== null)
    {
      $arr['data'] = $this->data;
    }
    return $arr;
  }

  // Standard JSON-RPC error codes
  public const PARSE_ERROR = -32700;
  public const INVALID_REQUEST = -32600;
  public const METHOD_NOT_FOUND = -32601;
  public const INVALID_PARAMS = -32602;
  public const INTERNAL_ERROR = -32603;
}
