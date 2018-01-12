# KriwebPhpApi

## For become a dealer

* [Kriweb Contact](https://kriweb.com/iletisim) - Apply for a dealership

## Installation
### This library automatically creates a token and refreshes a token every 500 seconds
```
<?php
include "class.Kriweb.php"; // Call library file
$api = new Kriweb();
$api->user("email","password"); // Login to Kriweb System
$api->test(true); // True or False (Not Required. Default condition is "false")
```
### Check Domain Availability

#### Check a domain
```
$api->isAvailable("fuuis.com");
```
#### Check multiple domains
```
$api->isAvailable(array("fuuis.com","freelyshout.com","kriweb.com"));
```
### Register a domain
```
$api->register("domainname.com", 1);
//$api->register("domain name", "register year min: 1, max: 9");
```
### Transfer a domain
```
$api->transfer("domainname.com", 1, "transfer code");
//$api->register("domain name", "register year min: 1, max: 9", "transfer code from other registrar");
```
