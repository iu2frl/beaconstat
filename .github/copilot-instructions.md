---
applyTo: "**"
---
# Project general coding standards

## Language information
- We are coding for PHP 8.2+ with CodeIgniter V4
- Stick as much as possible to the CodeIgniter V4 instructions and APIs
- Create custom PHP or JS functions only if strictly needed because nothing similar is already provided by CodeIgniter V4

## Naming Conventions
- Use PascalCase for component names, interfaces, and type aliases
- Use camelCase for variables, functions, and methods
- Prefix private class members with underscore (_)
- Use ALL_CAPS for constants

## Error Handling
- Use try/catch blocks for async operations
- Always log errors with contextual information

## Code styling
- Add blank line between different blocks of code to improve readability
- Add some comments to the code when some steps are not obvious or custom functions are used/created
- Do not add comments to simple operations that are easy to read
