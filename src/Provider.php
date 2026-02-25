<?php

namespace Kubex\MCP;

interface Provider
{
  /** @return ToolHandler[] */
  public function tools(): array;

  /** @return ResourceHandler[] */
  public function resources(): array;
}
