<?php

namespace Kubex\MCP;

class ListResourcesResult
{
  /** @var Resource[] */
  public array $resources = [];

  public function toArray(): array
  {
    return ['resources' => array_map(fn(Resource $r) => $r->toArray(), $this->resources)];
  }
}
