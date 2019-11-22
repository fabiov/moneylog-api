#!/bin/bash

#bin/console doctrine:sc:dr --force
#bin/console doctrine:sc:cr
bin/console doctrine:fi:lo -n
vendor/bin/simple-phpunit
