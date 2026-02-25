<?php

namespace Kubex\MCP;

interface Provider
{
  /** @return ToolHandler[] */
  public function tools(): array;

  /** @return ResourceHandler[] */
  public function resources(): array;
}

class ToolHandler
{
  /**
   * @param Tool $definition The tool definition
   * @param callable(string $workspaceID, ?array $arguments): CallToolResult $call Handler function
   */
  public function __construct(
    public Tool $definition,
    public mixed $call,
  ) {}
}

class ResourceHandler
{
  /**
   * @param string $scheme URI scheme this handler covers
   * @param string $name Display name
   * @param string $description Description
   * @param callable(string $workspaceID): Resource[] $list List resources for workspace
   * @param callable(string $workspaceID, string $uri): ResourceContent $read Read a resource by URI
   */
  public function __construct(
    public string $scheme,
    public string $name,
    public string $description,
    public mixed $list,
    public mixed $read,
  ) {}
}
