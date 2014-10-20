# Symfony Solr Playground

An example playground project to demonstrate usage of [Apache Solr](http://lucene.apache.org/solr/) with [Symfony 2](http://symfony.com/)

## Requirements

- PHP >= 5.4
- ext-curl
- ext-solr
- https://lucene.apache.org/solr/4_10_0/SYSTEM_REQUIREMENTS.html

## Installation

*1.) Clone this repository and install deps via composer*

```bash
git clone git@github.com:frne/symfony-solr-playground.git
cd symfony-solr-playground
composer.phar install
php app/console cache:clear
```
*2.) Install solr (4.x)*

On OSX, you need also ```greadlink```. You can install it using homebrew:
```bash
brew install coreutils solr
```

On Linux-based 

*3.) Run solr using this configuration*
```bash
solr `greadlink -f ./solr`
```
*4.) Generate some data using fixtures*

```bash
php app/console doctrine:fixtures:load
```
