#!/bin/bash

# New dependencies
# docker run --rm -v $(pwd):/app composer/composer require <LIB_NAME>

# Install libs described in composer.json
docker run --rm -v $(pwd):/app composer/composer install

# Run Apache + PHP with docker-compose.yml
docker-compose up