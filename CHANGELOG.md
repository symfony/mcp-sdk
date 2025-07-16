CHANGELOG
=========

0.1
---

 * Add Model Context Protocol (MCP) implementation for LLM-application communication
 * Add JSON-RPC based protocol handling with `JsonRpcHandler`
 * Add three core MCP capabilities:
   - Resources: File-like data readable by clients (API responses, file contents)
   - Tools: Functions callable by LLMs (with user approval)
   - Prompts: Pre-written templates for specific tasks
 * Add multiple transport implementations:
   - Symfony Console Transport for testing and CLI applications
   - Stream Transport supporting Server-Sent Events (SSE) and HTTP streaming
   - STDIO transport for command-line interfaces
 * Add capability chains for organizing features:
   - `ToolChain` for tool management
   - `ResourceChain` for resource management
   - `PromptChain` for prompt template management
 * Add Server component managing transport connections
 * Add request/notification handlers for MCP operations
 * Add standardized interface enabling LLMs to interact with external systems
 * Add support for building LLM "plugins" with extra context capabilities