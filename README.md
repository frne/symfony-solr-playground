# Symfony Solr Playground

An example playground project to demonstrate usage of [Apache Solr](http://lucene.apache.org/solr/) with [Symfony 2](http://symfony.com/)

## Requirements

- PHP >= 5.4
- ext-curl
- ext-solr
- https://lucene.apache.org/solr/4_10_0/SYSTEM_REQUIREMENTS.html

## Installation

**1.) Clone this repository and install deps via composer**

```bash
git clone git@github.com:frne/symfony-solr-playground.git
cd symfony-solr-playground
composer.phar install
php app/console cache:clear
```

**3.) Run solr using this configuration**

We run solr in foreground using the option ```-f``` , so we can see what it's up to... 

```bash
solr/bin/solr start -f
```

Hit ctrl+c to stop the server...

**4.) Generate some data using fixtures**

```bash
php app/console doctrine:fixtures:load
```
