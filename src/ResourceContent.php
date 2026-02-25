<?php

namespace Kubex\MCP;

class ResourceContent
{
  public function __construct(
    public string $uri,
    public string $mimeType = '',
    public string $text = '',
    public string $blob = '',
  ) {}

  public function toArray(): array
  {
    $arr = ['uri' => $this->uri];
    if($this->mimeType !== '')
    {
      $arr['mimeType'] = $this->mimeType;
    }
    if($this->text !== '')
    {
      $arr['text'] = $this->text;
    }
    if($this->blob !== '')
    {
      $arr['blob'] = $this->blob;
    }
    return $arr;
  }
}
