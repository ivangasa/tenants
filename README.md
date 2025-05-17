# 🏢 Tenants Project

Welcome 👋 to **Tenants** - a comprehensive implementation of multi-tenant architecture utilizing Domain-Driven Design and Hexagonal Architecture principles. This documentation will guide you through the setup and development process.

## 🚀 Project Overview

Tenants is an educational project demonstrating modern software development methodologies like DDD, Hexagonal Architecture and Clean Code.

The core concept: A single application serving multiple clients, each with dedicated domain and database, while sharing common configuration data. This architecture resembles a multi-tenant building where each tenant operates in their own isolated environment.
All Architecture Decision Records (ADRs) that guided the development of this project are available in the `docs/ADR` folder. 

📚 For more detailed information about the project, please refer to the [begin-here.md](docs/begin-here.md) document. 

## 🛠️ Quick Start Guide

### Prerequisites
- 🐳 Docker: Container platform for application deployment
- ⚙️ Your favourite Terminal

### Setup Instructions 
No long setups! Just execute this two commands on your terminal and the project will be ready for development:

```bash
# Build the Docker containers (this process may take several minutes)
make build

# Start the application
make start
```

## 🧰 Available Commands

### Development Commands
- `make build` - Builds Docker containers
- `make start` - Start Docker containers
- `make stop` - Stop Docker containers
- `make restart` - Restart Docker containers
- `make bash-api` - Access the API app container

### Code Quality Commands
- `make stan` - Execute PHPStan static analysis
- `make ecs` - Verify code style compliance
- `make ecs-fix` - Automatically fix code style issues
- `make infection` - Perform mutation testing
- `make deptrac` - Validate architectural boundaries

### Testing Commands
- `make tests` - Execute all test suites
- `make tests-unit` - Run unit tests
- `make tests-functional` - Execute functional tests
- `make tests-integration` - Run integration tests
- `make coverage` - Generate test coverage report
- `make infection` - Execute mutation testing with Infection

## 🏗️ Architecture Overview

This project implements a structured architectural approach:

- 📁 **Monorepo** - Consolidated codebase organization
- 🔍 **Bounded Contexts** - Domain-specific modules located in the `./src` directory
- 🤝 **Shared Context** - Common functionality in `./src/Shared`
- 🚪 **Multiple Apps** - Applications are in the `./apps` directory

The project implements Hexagonal Architecture with the following layers:
1. **Domain Layer** - Core business logic and rules
2. **Application Layer** - Orchestration of domain operations
3. **Infrastructure Layer** - External systems integration
4. **UI Layer** - User interface components

## 🛡️ Contribution Guidelines

To contribute to this project, all code must pass through GrumPHP validation tasks that ensure code quality and security:

- 🔒 **Security Checks** - Scans for vulnerabilities using Composer Audit and Symfony Security Checker
- 📐 **Architecture Validation** - Ensures compliance with architectural boundaries using Deptrac
- 🔍 **Static Analysis** - Detects potential bugs and errors with PHPStan
- 🧹 **Code Style** - Enforces coding standards with Easy Coding Standard (ECS)
- 🧬 **Mutation Testing** - Validates test quality using Infection

These checks run automatically on pre-commit hooks to maintain high code quality and security standards.

## 🧪 Testing Strategy

The project implements a comprehensive testing approach:
- 🔬 **Unit Tests** - Validation of individual components
- 🔭 **Functional Tests** - End-to-end behavior verification
- 🧩 **Integration Tests** - Component interaction validation
- 🧬 **Mutation Tests** - Advanced testing technique using Infection to ensure test quality by introducing "mutants" (small changes) in the code and verifying that tests can detect them. The project requires a minimum Mutation Score Indicator (MSI) of 80% and 100% for covered code.

## 🌐 PHP Infrastructure

- ⚡  **FrankenPHP** - High-performance PHP application server 
- 🎶 **Caddy** - Modern web server with automatic HTTPS

---

*Crafted with lots of ☕* by [Iván Saga](https://github.com/ivangasa)
