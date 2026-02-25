<?php

namespace Kubex\MCP;

class Resource
{
  public function __construct(
    public string $uri,
    public string $name,
    public string $description = '',
    public string $mimeType = '',
  ) {}

  public function toArray(): array
  {
    $arr = ['uri' => $this->uri, 'name' => $this->name];
    if($this->description !== '')
    {
      $arr['description'] = $this->description;
    }
    if($this->mimeType !== '')
    {
      $arr['mimeType'] = $this->mimeType;
    }
    return $arr;
  }
}
