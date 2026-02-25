<?php

namespace Kubex\MCP;

class ResourcesCapability
{
  public bool $subscribe = false;
  public bool $listChanged = false;

  public function toArray(): array
  {
    return ['subscribe' => $this->subscribe, 'listChanged' => $this->listChanged];
  }
}
