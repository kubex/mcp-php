<?php

namespace Kubex\MCP;

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
