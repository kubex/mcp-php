<?php

namespace Kubex\MCP;

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
