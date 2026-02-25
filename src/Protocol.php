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

class Resource
{
  public function __construct(
    public string $uri,
    public string $name,
    public string $description = '',
    public string $mimeType = '',
  ) {}

  public function toArray(): array
  {
    $arr = ['uri' => $this->uri, 'name' => $this->name];
    if($this->description !== '')
    {
      $arr['description'] = $this->description;
    }
    if($this->mimeType !== '')
    {
      $arr['mimeType'] = $this->mimeType;
    }
    return $arr;
  }
}

class ResourceContent
{
  public function __construct(
    public string $uri,
    public string $mimeType = '',
    public string $text = '',
    public string $blob = '',
  ) {}

  public function toArray(): array
  {
    $arr = ['uri' => $this->uri];
    if($this->mimeType !== '')
    {
      $arr['mimeType'] = $this->mimeType;
    }
    if($this->text !== '')
    {
      $arr['text'] = $this->text;
    }
    if($this->blob !== '')
    {
      $arr['blob'] = $this->blob;
    }
    return $arr;
  }
}

class Tool
{
  public function __construct(
    public string $name,
    public string $description = '',
    public array $inputSchema = ['type' => 'object'],
  ) {}

  public function toArray(): array
  {
    $arr = ['name' => $this->name, 'inputSchema' => $this->inputSchema];
    if($this->description !== '')
    {
      $arr['description'] = $this->description;
    }
    return $arr;
  }
}

class CallToolParams
{
  public string $name = '';
  public ?array $arguments = null;

  public static function fromArray(array $data): self
  {
    $p = new self();
    $p->name = $data['name'] ?? '';
    $p->arguments = $data['arguments'] ?? null;
    return $p;
  }
}

class CallToolResult
{
  /** @var ToolContent[] */
  public array $content = [];
  public bool $isError = false;

  public function toArray(): array
  {
    $arr = ['content' => array_map(fn(ToolContent $c) => $c->toArray(), $this->content)];
    if($this->isError)
    {
      $arr['isError'] = true;
    }
    return $arr;
  }
}

class ToolContent
{
  public function __construct(
    public string $type = 'text',
    public string $text = '',
    public string $data = '',
    public string $mimeType = '',
  ) {}

  public function toArray(): array
  {
    $arr = ['type' => $this->type];
    if($this->text !== '')
    {
      $arr['text'] = $this->text;
    }
    if($this->data !== '')
    {
      $arr['data'] = $this->data;
    }
    if($this->mimeType !== '')
    {
      $arr['mimeType'] = $this->mimeType;
    }
    return $arr;
  }
}

class InitializeResult
{
  public function __construct(
    public string $protocolVersion = '2024-11-05',
    public ?ServerCapabilities $capabilities = null,
    public ?Implementation $serverInfo = null,
  ) {}

  public function toArray(): array
  {
    return [
      'protocolVersion' => $this->protocolVersion,
      'capabilities' => $this->capabilities?->toArray() ?? new \stdClass(),
      'serverInfo' => $this->serverInfo?->toArray() ?? new \stdClass(),
    ];
  }
}

class ServerCapabilities
{
  public ?ResourcesCapability $resources = null;
  public ?ToolsCapability $tools = null;

  public function toArray(): array
  {
    $arr = [];
    if($this->resources !== null)
    {
      $arr['resources'] = $this->resources->toArray();
    }
    if($this->tools !== null)
    {
      $arr['tools'] = $this->tools->toArray();
    }
    return $arr;
  }
}

class Implementation
{
  public function __construct(
    public string $name = '',
    public string $version = '',
  ) {}

  public function toArray(): array
  {
    return ['name' => $this->name, 'version' => $this->version];
  }
}

class ResourcesCapability
{
  public bool $subscribe = false;
  public bool $listChanged = false;

  public function toArray(): array
  {
    return ['subscribe' => $this->subscribe, 'listChanged' => $this->listChanged];
  }
}

class ToolsCapability
{
  public bool $listChanged = false;

  public function toArray(): array
  {
    return ['listChanged' => $this->listChanged];
  }
}

class ListResourcesResult
{
  /** @var Resource[] */
  public array $resources = [];

  public function toArray(): array
  {
    return ['resources' => array_map(fn(Resource $r) => $r->toArray(), $this->resources)];
  }
}

class ReadResourceParams
{
  public string $uri = '';

  public static function fromArray(array $data): self
  {
    $p = new self();
    $p->uri = $data['uri'] ?? '';
    return $p;
  }
}

class ReadResourceResult
{
  /** @var ResourceContent[] */
  public array $contents = [];

  public function toArray(): array
  {
    return ['contents' => array_map(fn(ResourceContent $c) => $c->toArray(), $this->contents)];
  }
}

class ListToolsResult
{
  /** @var Tool[] */
  public array $tools = [];

  public function toArray(): array
  {
    return ['tools' => array_map(fn(Tool $t) => $t->toArray(), $this->tools)];
  }
}
