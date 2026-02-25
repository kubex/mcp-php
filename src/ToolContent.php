<?php

namespace Kubex\MCP;

class ToolContent
{
  public function __construct(
    public string $type = 'text',
    public string $text = '',
    public string $data = '',
    public string $mimeType = '',
  ) {}

  public function toArray(): array
  {
    $arr = ['type' => $this->type];
    if($this->text !== '')
    {
      $arr['text'] = $this->text;
    }
    if($this->data !== '')
    {
      $arr['data'] = $this->data;
    }
    if($this->mimeType !== '')
    {
      $arr['mimeType'] = $this->mimeType;
    }
    return $arr;
  }
}
