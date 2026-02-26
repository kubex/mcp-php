<?php

namespace Kubex\MCP;

class ToolHandler
{
  /**
   * @param Tool $definition The tool definition
   * @param callable(string $workspaceID, ?array $arguments, array $headers): CallToolResult $call Handler function
   */
  public function __construct(
    public Tool $definition,
    public mixed $call,
  ) {}
}
