#!/bin/bash

bin/console doctrine:sc:dr --force 
bin/console doctrine:sc:cr 
vendor/bin/simple-phpunit
