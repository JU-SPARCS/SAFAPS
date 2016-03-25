#!/bin/bash

exec /var/www/safaps/app/console rabbitmq:consumer -m 10 upload_profile
