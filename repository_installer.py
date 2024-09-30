import subprocess
import os
import sys
import time
import logging
import shutil

# Set up logging
logging.basicConfig(filename='minicore_install.log', level=logging.INFO, 
                    format='%(asctime)s - %(levelname)s - %(message)s')

# 1. Function to check if a package is already installed
def check_installation(command, package_name):
    try:
        result = subprocess.run(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        if result.returncode != 0:
            logging.warning(f"{package_name} not found. Attempting to install...")
            install_package(package_name)
        else:
            logging.info(f"{package_name} already installed.")
    except Exception as e:
        logging.error(f"Error checking {package_name}: {e}")

# 2. Function to install a package (supports different OS types)
def install_package(package_name):
    try:
        if os.path.exists("/etc/debian_version"):
            subprocess.run(['sudo', 'apt-get', 'install', '-y', package_name], check=True)
        elif os.path.exists("/etc/redhat-release"):
            subprocess.run(['sudo', 'yum', 'install', '-y', package_name], check=True)
        else:
            logging.error(f"Unsupported OS for automatic installation of {package_name}")
            sys.exit(1)
    except Exception as e:
        logging.error(f"Failed to install {package_name}: {e}")
        sys.exit(1)

# 3. Install Docker, Docker Compose, Redis, Kubernetes, etc.
def install_dependencies():
    logging.info("Checking and installing dependencies...")
    check_installation(["docker", "--version"], "docker.io")
    check_installation(["docker-compose", "--version"], "docker-compose")
    check_installation(["kubectl", "version", "--client"], "kubelet kubeadm kubectl")
    check_installation(["redis-server", "--version"], "redis-server")

# 4. Function to delete the entire target directory


# 1. Function to recursively delete a directory and its contents using os
def delete_directory(target_dir):
    if os.path.exists(target_dir):
        logging.info(f"Deleting directory: {target_dir}")
        try:
            # Walk through the directory and delete files and subdirectories
            for root, dirs, files in os.walk(target_dir, topdown=False):
                for file in files:
                    file_path = os.path.join(root, file)
                    os.remove(file_path)
                    logging.info(f"Deleted file: {file_path}")
                for dir in dirs:
                    dir_path = os.path.join(root, dir)
                    os.rmdir(dir_path)
                    logging.info(f"Deleted directory: {dir_path}")
            
            # Finally, remove the root directory itself
            os.rmdir(target_dir)
            logging.info(f"Directory {target_dir} successfully deleted.")
        except Exception as e:
            logging.error(f"Failed to delete the directory {target_dir}: {e}")
            sys.exit(1)
    else:
        logging.info(f"Directory {target_dir} does not exist. No need to delete.")
# 5. Clone the mini-core from GitHub
def clone_minicore_repo(repo_url, target_dir):
    if not os.path.exists(target_dir):
        os.makedirs(target_dir)  # Create the directory if it doesn't exist
    try:
        subprocess.run(['git', 'clone', repo_url, target_dir], check=True)
        logging.info(f"Cloned mini-core repository into {target_dir}.")
    except subprocess.CalledProcessError as e:
        logging.error(f"Failed to clone the repository: {e}")
        sys.exit(1)

# 6. Create the Docker Compose file to set up services
def create_docker_compose(target_dir):
    compose_content = """
    version: '3.8'
    services:
      redis:
        image: "redis:alpine"
        ports:
          - "6379:6379"
      core_app:
        build: .
        volumes:
          - .:/app
        ports:
          - "80:80"
        depends_on:
          - redis
    """
    try:
        with open(os.path.join(target_dir, "docker-compose.yaml"), "w") as f:
            f.write(compose_content)
        logging.info("Docker Compose file created.")
    except Exception as e:
        logging.error(f"Failed to create Docker Compose file: {e}")
        sys.exit(1)

# 7. Check if Docker Desktop is running
def check_docker_running():
    try:
        result = subprocess.run(['docker', 'info'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        if result.returncode != 0:
            logging.error("Docker Desktop is not running or misconfigured. Please ensure Docker is running.")
            sys.exit(1)
    except subprocess.CalledProcessError as e:
        logging.error(f"Error while checking Docker status: {e}")
        sys.exit(1)

# 8. Start Docker containers with retries
def start_containers(target_dir, retries=3, delay=5):
    check_docker_running()
    for i in range(retries):
        try:
            subprocess.run(['docker-compose', 'up', '--build', '-d'], cwd=target_dir, check=True)
            logging.info("Containers started successfully.")
            break
        except subprocess.CalledProcessError as e:
            logging.error(f"Failed to start Docker containers: {e}. Retrying in {delay} seconds...")
            time.sleep(delay)
    else:
        logging.error("Failed to start Docker containers after multiple retries.")
        sys.exit(1)

# Main script to automate everything
if __name__ == "__main__":
    REPO_URL = "https://github.com/badwolfzm/axiomatic-mini-core.git"
    TARGET_DIR = "c:/minicore"
    delete_directory(TARGET_DIR)
    logging.basicConfig(filename='minicore_install.log', level=logging.INFO, 
                    format='%(asctime)s - %(levelname)s - %(message)s')
    # Install necessary dependencies
    install_dependencies()

    # Delete the target directory before installation
    delete_directory(TARGET_DIR)

    # Clone the mini-core repository
    clone_minicore_repo(REPO_URL, TARGET_DIR)

    # Create Docker Compose file
    create_docker_compose(TARGET_DIR)

    # Start Docker containers
    start_containers(TARGET_DIR)

    logging.info("Mini-core system installed, directory deleted, and Docker containers are up.")
