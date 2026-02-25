<?php

namespace Kubex\MCP;

class ServerCapabilities
{
  public ?ResourcesCapability $resources = null;
  public ?ToolsCapability $tools = null;

  public function toArray(): array
  {
    $arr = [];
    if($this->resources !== null)
    {
      $arr['resources'] = $this->resources->toArray();
    }
    if($this->tools !== null)
    {
      $arr['tools'] = $this->tools->toArray();
    }
    return $arr;
  }
}
