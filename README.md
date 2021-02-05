# ubi-transport-api
##Installation
Clone the project and navigate to the project folder

`cd ubitransport-api;`

Install Make if not installed yet

`sudo apt install make`

Run `make start` to start docker containers

Run `make install` to install dependencies, create database, update schema and load fixtures.

##Testing
Run `make test`

_Note that it will reset current dev database as it is using same docker containers._

## Usage
**Use the dedicated Vue.js app ubitransport-app**
Or Postman to test the routes

## API Documentation
Documentation can be found at: localhost/api/doc
