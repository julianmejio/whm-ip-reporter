WHM IP address reporter
=======================

Simple IP address abuse reporter for WHM. It uses
[AbuseIPDB](https://www.abuseipdb.com/) as the primary abuse database to report,
and Modsec Tools and cpHulk as data gather.

Installation
------------

1. Clone the project
2. Install the dependencies: `composer install --no-dev -a`
3. Copy the *.env* file and put the correct values in there: `cp .env.dist .env`
4. Create the database: `./vendor/bin/doctrine orm:schema-tool:create`

Create the tokens and keys as you need.

Usage
-----

Pull the Modsec and cpHulk data into the database:

```shell script
./console.php whm:pull-modsec-log
./console.php whm:pull-hulk-log
```

And then send it to AbuseIPDB: `./console report:send:abuseipdb`
