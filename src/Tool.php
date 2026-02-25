<?php

namespace Kubex\MCP;

class Tool
{
  public function __construct(
    public string $name,
    public string $description = '',
    public array $inputSchema = ['type' => 'object'],
  ) {}

  public function toArray(): array
  {
    $arr = ['name' => $this->name, 'inputSchema' => $this->inputSchema];
    if($this->description !== '')
    {
      $arr['description'] = $this->description;
    }
    return $arr;
  }
}
