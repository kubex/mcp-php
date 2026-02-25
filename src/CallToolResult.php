<?php

namespace Kubex\MCP;

class CallToolResult
{
  /** @var ToolContent[] */
  public array $content = [];
  public bool $isError = false;

  public function toArray(): array
  {
    $arr = ['content' => array_map(fn(ToolContent $c) => $c->toArray(), $this->content)];
    if($this->isError)
    {
      $arr['isError'] = true;
    }
    return $arr;
  }
}
