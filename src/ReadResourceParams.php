<?php

namespace Kubex\MCP;

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
