

```markdown
# Axiomatic Mini-Core

The **Axiomatic Mini-Core** is a modular microservice framework built using PHP 8.1 and Docker. This repository contains the full source code, including services, plugins, and a dynamic orchestrator that helps handle microservices in an easy and flexible way.

This project also includes an automated Python script (`repository_installer.py`) to help set up the environment, install dependencies, and manage the Docker containers necessary to run the Mini-Core system.

## Features

- Modular microservice architecture
- Easily customizable service registry and plugin-based microservices
- Fault-tolerant and scalable
- Preconfigured with Docker for easy containerized deployment
- Uses Redis as a caching mechanism for performance

## Requirements

Before installing and running the Mini-Core system, ensure that your machine meets the following prerequisites:

- **Python 3.x**
- **Git** installed
- **Docker** and **Docker Compose** installed
- **Kubernetes** (optional, but supported)
- **Redis** (handled automatically by Docker)

## Quick Start

### 1. Clone the Repository

First, you need to clone the repository into your local environment:

```bash
git clone https://github.com/badwolfzm/axiomatic-mini-core.git
cd axiomatic-mini-core
```

### 2. Install Dependencies Automatically

Use the `repository_installer.py` script provided in the repository to automatically install the necessary packages and dependencies.

```bash
python3 repository_installer.py
```

This script performs the following tasks:

- Checks and installs dependencies like Docker, Docker Compose, Redis, and Kubernetes (if needed).
- Deletes any pre-existing `minicore` directory.
- Clones the Mini-Core repository.
- Sets up the required `docker-compose.yaml` file to run services.
- Starts the Docker containers to run the Mini-Core framework.

### 3. Running the System Manually

If you prefer to manually run the system without the Python installer script, follow these steps:

1. **Install Dependencies**:
   - Make sure Docker, Docker Compose, and Redis are installed and running on your system.

2. **Clone the Repository**:
   ```bash
   git clone https://github.com/badwolfzm/axiomatic-mini-core.git
   ```

3. **Build and Run Docker Containers**:
   Navigate to the cloned directory and use Docker Compose to build and start the services:

   ```bash
   cd axiomatic-mini-core
   docker-compose up --build -d
   ```

   This will spin up the required Docker containers for running the Mini-Core system. The `core_app` service (your PHP application) will be accessible on port 80, and Redis will run on port 6379.

### 4. Stopping and Restarting the Containers

You can stop the Docker containers using the following command:

```bash
docker-compose down
```

To restart the containers:

```bash
docker-compose up -d
```

### 5. Accessing the Logs

Logs for the installer script can be found in the file `minicore_install.log`. You can use this file to debug any issues that arise during the installation process.

## Directory Structure

```
axiomatic-mini-core/
├── config/                         # Core system configuration files
├── logs/                           # Application logs
├── plugins/                        # Microservice plugin files
├── public/                         # Public directory for web server
├── src/                            # Core framework and services
│   ├── Core/                       # Core framework components
│   ├── Services/                   # Example services (EmailService, SMSService, etc.)
├── docker-compose.yaml             # Docker Compose configuration file
├── repository_installer.py         # Python installer script
├── README.md                       # This readme file
└── .env                            # Environment variables (if needed)
```

### Services and Plugins

The framework includes a couple of example services:

- **EmailService**: Service for sending emails using the configured SMTP settings.
- **SMSService**: Service for sending SMS messages using the configured API.
- **LogService**: Service to log actions and messages into the system.

You can add additional services as plugins by following the same structure.

## Customization

- **Adding Services**: You can add new services to the framework by creating a new service class in the `src/Services` directory and registering it in the configuration.
  
- **Modifying Docker Configuration**: You can edit the `docker-compose.yaml` file to add additional services, change ports, or integrate with other microservices.

## Troubleshooting

If the Python installer script fails or you encounter issues while running the framework:

1. Check the `minicore_install.log` file for any error messages.
2. Ensure Docker and Docker Compose are installed and running properly.
3. Make sure all dependencies are installed by running the following commands manually:
   ```bash
   docker --version
   docker-compose --version
   redis-server --version
   ```

If you continue to experience issues, feel free to open an issue on the repository.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
```

---

### **Explanation of the README:**

- **Quick Start**: Provides a streamlined process for users to quickly clone the repository and use the Python installer script to set up the environment.
- **Manual Installation**: For users who prefer manual control, I’ve included instructions on how to manually build and run Docker containers.
- **Directory Structure**: Details the project structure and where core components like plugins and services reside.
- **Customization**: Encourages users to expand the system by adding new services or modifying existing Docker configurations.
- **Troubleshooting**: Offers basic troubleshooting steps and tips for resolving common issues.

This `README.md` should provide users with everything they need to get started with the Axiomatic Mini-Core system. Let me know if you need additional changes or clarifications!