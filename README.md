# Drupal docker base project

My own drupal project template with a docker environment and different tools for code quality.

# Requirements
You need to have some tools to use this template :
* Make
* Git
* [Docker](https://www.digitalocean.com/community/tutorial_collections/how-to-install-and-use-docker)
* [Docker compose](https://www.digitalocean.com/community/tutorial_collections/how-to-install-docker-compose)

* In case you use a Docker compose version inferior to v2.0, you need to replace all `docker compose` of the Makefile by `docker-compose`
* Same for the file `grumphp.yml`

# Installation

## Configuration

You need to configure variables in `.env.dev` or create a new file `.env.your_environment_name`

Same for docker-compose you need to use `docker-compose.dev.yml` or create a new one
like `docker-compose.your_environment_name`

## Init

If you use by default `.env.dev` so execute this command :
```bash
make new-project
```

If you use a custom environment file like `.env.your_environment_name` execute :
```bash
make new-project your_environment_name
```

## Access the website

You have access to your environement by typing `localhost:port_you_configured_in_env`

