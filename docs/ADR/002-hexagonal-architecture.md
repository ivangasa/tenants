# 2. Hexagonal Architecture

Date: 2023-11-16

## Status

Accepted

## Context

When developing a multi-tenant application, we need an architecture that allows for:
- Clear separation of concerns
- Domain-driven design principles
- Testability at all levels
- Independence from external frameworks and tools
- Flexibility to evolve and adapt to changing requirements

## Decision

We have decided to implement a Hexagonal Architecture (also known as Ports and Adapters) with elements of Clean Architecture and Domain-Driven Design (DDD). The architecture is organized into the following layers:

### 1. Domain Layer

- **Purpose**: Contains the core business logic, entities, value objects, and domain services
- **Characteristics**:
  - Has no dependencies on other layers or external frameworks
  - Defines interfaces (ports) that other layers must implement
  - Contains pure business logic with no infrastructure concerns
  - Uses value objects for immutable concepts defined by their attributes
  - Implements domain events for communication between aggregates

### 2. Application Layer

- **Purpose**: Orchestrates the flow of data and coordinates domain objects to perform use cases
- **Characteristics**:
  - Depends only on the Domain layer
  - Contains use cases/application services
  - Implements the Command/Query pattern through command and query handlers
  - Acts as a facade to the domain layer for external clients

### 3. Infrastructure Layer

- **Purpose**: Provides implementations for interfaces defined in the domain layer
- **Characteristics**:
  - Depends on Domain and Application layers
  - Contains adapters for external services and frameworks
  - Implements repositories, messaging, and other technical concerns
  - Uses Symfony Messenger for implementing the Command Bus pattern

### 4. UI Layer

- **Purpose**: Handles user interaction and presents information to users
- **Characteristics**:
  - Depends on Application and Domain layers
  - Contains controllers, presenters, and view models
  - Transforms user input into commands/queries for the application layer
  - Handles HTTP requests and responses

### Messaging Patterns

We've implemented the Command Bus pattern using Symfony Messenger to:
- Decouple command execution from command creation
- Enable asynchronous processing when needed
- Provide a clear entry point for application use cases

### Project Structure

The project follows a modular structure:
- `src/Shared/` contains shared components used across modules
- `apps/api/` contains the API application built with Symfony
- Each module follows the same layered architecture

## Consequences

### Positive

- Clear separation of concerns makes the codebase easier to understand and maintain
- Domain logic is isolated from infrastructure concerns, making it more testable
- The architecture supports the principles of Domain-Driven Design
- Changes to external dependencies have minimal impact on the core business logic
- The modular structure allows for independent development and deployment of features
- Command Bus pattern provides a clear entry point for application use cases

### Negative

- More complex initial setup compared to traditional MVC architectures
- Requires discipline to maintain the architectural boundaries
- May involve more boilerplate code for simple use cases
- Learning curve for developers not familiar with hexagonal architecture

### Mitigations

- Deptrac is used to enforce architectural boundaries
- Shared base classes reduce boilerplate code
- Comprehensive documentation and examples help onboard new developers
- Automated tests verify that the architecture is correctly implemented

## References

- [Hexagonal Architecture (Alistair Cockburn)](https://alistair.cockburn.us/hexagonal-architecture/)
- [Clean Architecture (Robert C. Martin)](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Domain-Driven Design (Eric Evans)](https://domainlanguage.com/ddd/)
- [Symfony Messenger Component](https://symfony.com/doc/current/messenger.html)