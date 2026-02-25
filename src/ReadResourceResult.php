<?php

namespace Kubex\MCP;

class ReadResourceResult
{
  /** @var ResourceContent[] */
  public array $contents = [];

  public function toArray(): array
  {
    return ['contents' => array_map(fn(ResourceContent $c) => $c->toArray(), $this->contents)];
  }
}
