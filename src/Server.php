<?php

namespace Kubex\MCP;

class Server
{
  private string $_name;
  private string $_version;
  /** @var ResourceHandler[] */
  private array $_resources = [];
  /** @var ToolHandler[] */
  private array $_tools = [];

  public function __construct(string $name, string $version)
  {
    $this->_name = $name;
    $this->_version = $version;
  }

  public function addProvider(Provider $provider): void
  {
    array_push($this->_tools, ...$provider->tools());
    array_push($this->_resources, ...$provider->resources());
  }

  /**
   * Handle a raw JSON-RPC 2.0 request body and return a JSON response string.
   *
   * @param string $rawBody The raw HTTP request body
   * @param array  $headers Associative array of HTTP headers (key => value)
   *
   * @return string JSON-RPC 2.0 response
   */
  public function handle(string $rawBody, array $headers = []): string
  {
    $req = JSONRPCRequest::fromJSON($rawBody);
    if($req === null)
    {
      return $this->_errorResponse(null, JSONRPCError::PARSE_ERROR, 'Invalid JSON');
    }

    if($req->jsonrpc !== '2.0')
    {
      return $this->_errorResponse($req->id, JSONRPCError::INVALID_REQUEST, 'Invalid JSON-RPC version');
    }

    $workspaceID = $headers['X-Workspace-ID'] ?? $headers['x-workspace-id'] ?? '';

    $result = null;
    $error = null;

    switch($req->method)
    {
      case 'initialize':
        $result = $this->_handleInitialize();
        break;
      case 'notifications/initialized':
        $result = new \stdClass();
        break;
      case 'ping':
        $result = new \stdClass();
        break;
      case 'resources/list':
        [$result, $error] = $this->_handleResourcesList($workspaceID);
        break;
      case 'resources/read':
        [$result, $error] = $this->_handleResourcesRead($workspaceID, $req->params);
        break;
      case 'tools/list':
        $result = $this->_handleToolsList();
        break;
      case 'tools/call':
        [$result, $error] = $this->_handleToolsCall($workspaceID, $req->params);
        break;
      default:
        $error = new JSONRPCError(JSONRPCError::METHOD_NOT_FOUND, 'Method not found');
        break;
    }

    return $this->_buildResponse($req->id, $result, $error);
  }

  private function _handleInitialize(): array
  {
    $caps = new ServerCapabilities();
    $caps->resources = new ResourcesCapability();

    if(count($this->_tools) > 0)
    {
      $caps->tools = new ToolsCapability();
    }

    $result = new InitializeResult(
      protocolVersion: '2024-11-05',
      capabilities: $caps,
      serverInfo: new Implementation($this->_name, $this->_version),
    );

    return $result->toArray();
  }

  private function _handleResourcesList(string $workspaceID): array
  {
    $resources = [];
    foreach($this->_resources as $handler)
    {
      try
      {
        $items = ($handler->list)($workspaceID);
        array_push($resources, ...$items);
      }
      catch(\Throwable $e)
      {
        error_log("mcp: error listing resources for scheme {$handler->scheme}: {$e->getMessage()}");
        return [null, new JSONRPCError(JSONRPCError::INTERNAL_ERROR, 'Internal server error')];
      }
    }

    $result = new ListResourcesResult();
    $result->resources = $resources;
    return [$result->toArray(), null];
  }

  private function _handleResourcesRead(string $workspaceID, mixed $params): array
  {
    if(empty($params))
    {
      return [null, new JSONRPCError(JSONRPCError::INVALID_PARAMS, 'Missing params')];
    }

    $p = ReadResourceParams::fromArray(is_array($params) ? $params : []);
    if($p->uri === '')
    {
      return [null, new JSONRPCError(JSONRPCError::INVALID_PARAMS, 'Invalid params')];
    }

    $parts = explode('://', $p->uri, 2);
    if(count($parts) !== 2)
    {
      return [null, new JSONRPCError(JSONRPCError::INVALID_PARAMS, 'Invalid URI format')];
    }
    $scheme = $parts[0];

    foreach($this->_resources as $handler)
    {
      if($handler->scheme === $scheme)
      {
        try
        {
          $content = ($handler->read)($workspaceID, $p->uri);
          $result = new ReadResourceResult();
          $result->contents = [$content];
          return [$result->toArray(), null];
        }
        catch(\Throwable $e)
        {
          error_log("mcp: error reading resource {$p->uri}: {$e->getMessage()}");
          return [null, new JSONRPCError(JSONRPCError::INTERNAL_ERROR, 'Internal server error')];
        }
      }
    }

    return [null, new JSONRPCError(JSONRPCError::INVALID_PARAMS, 'Unknown resource scheme')];
  }

  private function _handleToolsList(): array
  {
    $tools = [];
    foreach($this->_tools as $handler)
    {
      $tools[] = $handler->definition->toArray();
    }
    return ['tools' => $tools];
  }

  private function _handleToolsCall(string $workspaceID, mixed $params): array
  {
    if(empty($params))
    {
      return [null, new JSONRPCError(JSONRPCError::INVALID_PARAMS, 'Missing params')];
    }

    $p = CallToolParams::fromArray(is_array($params) ? $params : []);

    foreach($this->_tools as $handler)
    {
      if($handler->definition->name === $p->name)
      {
        try
        {
          /** @var CallToolResult $result */
          $result = ($handler->call)($workspaceID, $p->arguments);
          return [$result->toArray(), null];
        }
        catch(\Throwable $e)
        {
          $result = new CallToolResult();
          $result->content = [new ToolContent(type: 'text', text: 'error: ' . $e->getMessage())];
          $result->isError = true;
          return [$result->toArray(), null];
        }
      }
    }

    return [null, new JSONRPCError(JSONRPCError::METHOD_NOT_FOUND, "Unknown tool: {$p->name}")];
  }

  private function _buildResponse(mixed $id, mixed $result, ?JSONRPCError $error): string
  {
    $resp = new JSONRPCResponse();
    $resp->id = $id;
    if($error !== null)
    {
      $resp->error = $error;
    }
    else
    {
      $resp->result = $result;
    }
    return $resp->toJSON();
  }

  private function _errorResponse(mixed $id, int $code, string $message): string
  {
    return $this->_buildResponse($id, null, new JSONRPCError($code, $message));
  }
}
