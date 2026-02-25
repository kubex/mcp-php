<?php

namespace Kubex\MCP;

class ToolsCapability
{
  public bool $listChanged = false;

  public function toArray(): array
  {
    return ['listChanged' => $this->listChanged];
  }
}
