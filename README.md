# NewsHubAPI

**NewsHubAPI** is a Laravel-based news aggregation API that pulls articles from multiple sources and provides a personalized feed. This project is Dockerized for easy setup and deployment.

## Prerequisites

- **Docker** and **Docker Compose** should be installed on your machine.

## Setup Instructions

### 1. Clone the Repository

Clone this repository to your local machine:

````bash
git clone https://github.com/yourusername/NewsHubAPI.git
cd NewsHubAPI

### Build the Docker Containers

Run the following command to build the Docker containers:

```bash
docker-compose up -d


### Configure the Environment


Create a `.env` file in the root directory. Use the following template for your configuration:

```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=newshub
DB_USERNAME=root
DB_PASSWORD=root

NEWSAPI_KEY=YOUR_NEWSAPI_KEY

### Run Database Migrations

After setting up the environment and starting the Docker containers, run the following command to execute database migrations:

```bash
php artisan migrate

### 4. Fetch News Data

To fetch news data using the custom Artisan command, run the following:

```bash
php artisan news:fetch








````
