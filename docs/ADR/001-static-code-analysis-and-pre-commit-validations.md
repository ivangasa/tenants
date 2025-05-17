# 1. Static Code Analysis and Pre-commit Validations

Date: 2023-11-15

## Status

Accepted

## Context

In order to maintain high code quality and prevent issues from being introduced into the codebase, we need to implement 
automated checks that run before code is committed to the repository. These checks should enforce coding standards, 
detect potential bugs, ensure architectural constraints are followed, and verify that tests are effective.

## Decision

We have decided to implement a comprehensive set of static code analysis tools and pre-commit validations using GrumPHP 
as the task runner. The following tools have been integrated:

### 1. Composer Validation

- **Tool**: GrumPHP Composer task
- **Purpose**: Validates composer.json and composer.lock files to ensure they are valid and in sync
- **Configuration**: Strict mode enabled, checks dependencies and prevents local repositories

### 2. Security Checks

- **Tools**: 
  - Composer Audit
  - Symfony Security Checker
- **Purpose**: Detect security vulnerabilities in dependencies
- **Configuration**: Reports abandoned packages and checks both production and development dependencies

### 3. Architectural Constraints

- **Tool**: Deptrac
- **Purpose**: Enforces architectural dependencies between layers
- **Configuration**: Defines four layers (Application, Domain, Infrastructure, Ui) with strict dependency rules:
  - Application can depend on Domain
  - Domain has no dependencies
  - Infrastructure can depend on Application and Domain
  - Ui can depend on Application and Domain

### 4. Static Analysis

- **Tool**: PHPStan
- **Purpose**: Performs static analysis to detect potential bugs and issues
- **Configuration**: Set to maximum level for strictest analysis on all PHP files in apps/, src/, and tests/ directories

### 5. Coding Standards

- **Tool**: Easy Coding Standard (ECS)
- **Purpose**: Enforces consistent coding style across the project
- **Configuration**: Applies multiple rule sets (PSR-12, Symplify, etc.) with custom configurations for specific rules

### 6. Mutation Testing

- **Tool**: Infection
- **Purpose**: Evaluates the quality of tests by making small changes to the code and checking if tests detect these changes
- **Configuration**: 
  - Minimum Mutation Score Indicator (MSI): 80%
  - Minimum Covered MSI: 100%
  - Uses all default mutators

## Consequences

### Positive

- Improved code quality through automated enforcement of standards and best practices
- Early detection of potential bugs and security vulnerabilities
- Consistent code style across the project
- Enforced architectural boundaries preventing unwanted dependencies
- Higher test quality through mutation testing
- Reduced technical debt by preventing problematic code from entering the codebase

### Negative

- Increased time to commit changes due to validation checks
- Learning curve for developers to understand and comply with all the enforced rules
- Potential for false positives that may need to be addressed or ignored

### Mitigations

- Caching is enabled for most tools to improve performance
- Configuration files are well-documented with comments and links to documentation
- Tools are configured to run in parallel where possible

## References

- [GrumPHP Documentation](https://github.com/phpro/grumphp)
- [PHPStan Documentation](https://phpstan.org/user-guide/getting-started)
- [Deptrac Documentation](https://github.com/qossmic/deptrac)
- [Easy Coding Standard Documentation](https://github.com/symplify/easy-coding-standard)
- [Infection Documentation](https://infection.github.io/guide/)