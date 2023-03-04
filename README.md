# Nette <-> Phoenix registrator
Integration of [Phoenix](https://github.com/lulco/phoenix) Database Migrations into Nette Framework

[![Build Status](https://travis-ci.org/lulco/nette-phoenix-registrator.svg?branch=master)](https://travis-ci.org/lulco/nette-phoenix-registrator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lulco/nette-phoenix-registrator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lulco/nette-phoenix-registrator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/lulco/nette-phoenix-registrator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lulco/nette-phoenix-registrator/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/lulco/nette-phoenix-registrator.svg)](https://packagist.org/packages/lulco/nette-phoenix-registrator)
[![Total Downloads](https://img.shields.io/packagist/dt/lulco/nette-phoenix-registrator.svg?style=flat-square)](https://packagist.org/packages/lulco/nette-phoenix-registrator)
[![PHP 7 supported](http://php7ready.timesplinter.ch/lulco/nette-phoenix-registrator/master/badge.svg)](https://travis-ci.org/lulco/nette-phoenix-registrator)

Reads container and creates Phoenix configuration based on files stored in it. Your database connections have to be set in this structure:
```
parameters:
    database:
        default:
            adapter: ADAPTER
            host: HOST
            port: PORT # optional
            user: USER
            password: PASSWORD
            dbname: DBNAME
            charset: CHARSET # optional         
```
