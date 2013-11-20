ConfView Apigility Web Service
==============================

A simple webservice to provide some statistics about an event that is on [joind.in](https://joind.in)



Installation
------------

* Download the [zip file](https://github.com/akrabat/confview-api/archive/master.zip).
* Install composer and then run `composer.phar update`

Now, fire it up! Do one of the following:

- Create a vhost in your web server that points the DocumentRoot to the
  `public/` directory of the project
- Fire up the built-in web server in PHP (5.4.8+) (**note**: do not use this for
  production!)

In the latter case, do the following:

```bash
    cd path/to/install
    php -S 0:8080 -t public/ public/index.php
```

You can then visit the site at http://localhost:8080/

## Curl examples:

View list of events:

    curl -s -H "Accept: application/vnd.conference.v1+json" http://localhost:8080/conference


Inspect an event:

    curl -s -H "Accept: application/vnd.conference.v1+json" "http://localhost:8080/conference/https%253A%252F%252Fapi.joind.in%252Fv2.1%252Fevents%252F1371"

or
    curl -s -H "Accept: application/vnd.conference.v1+json" "http://localhost:8080/conference/https%253A%252F%252Fapi.joind.in%252Fv2.1%252Fevents%252F1546"



