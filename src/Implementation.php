<?php

namespace Kubex\MCP;

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
