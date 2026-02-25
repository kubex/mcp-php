<?php

namespace Kubex\MCP;

class ListToolsResult
{
  /** @var Tool[] */
  public array $tools = [];

  public function toArray(): array
  {
    return ['tools' => array_map(fn(Tool $t) => $t->toArray(), $this->tools)];
  }
}
