SMS Service Center API client version 2
===============================

This repository contains the open source PHP client for version 2 of SMS Service Center's API.

Requirements
-----

- [Sign up](https://smsservicecenter.nl/register) for a free SMS Service Center account
- Get your access_token in the API section
- MessageBird API client for PHP requires PHP >= 5.5.0.
- Composer

Installation
-----
We currenly only support installing the API client using Composer. 

- [Download composer](https://getcomposer.org/doc/00-intro.md#installation-nix)
- Run `composer require zeauw/ssc-api-v2`.

Usage
-----
Set up new instance of the object. Pass your access_token as the first parameter:
```php
// Include composers autoloader
include "vendor/autoload.php";
// Create the object
$client = new \Zeauw\SSCAPIv2\Client("ENTER_YOUR_ACCESS_TOKEN_HERE");
```

Get your creditcount:
```php
$credits = $client->getCredits();
var_dump($credits); // int
```

Get current sendname:
```php
$sendname = $client->getSendname();
var_dump($sendname); // string
```

Get messages send:
```php
$history = $client->getHistory();
var_dump($history); // array
```

Send a message to a single receiver:
```php
$is_send = $client->send(
	"0612345678",			// Single recipient 
	"Enter message to send here"	// Message to send
);
var_dump($is_send); // bool
```

Send a message to multiple recipients (max 50):
```php
$is_send = $client->send(
	["0612345678","0687654321"],	// Array of recipients
	"Enter message to send here"	// Message to send
);
var_dump($is_send); // bool
```

Send a message with a different sendname than the one stored:
```php
$is_send = $client->send(
	"0612345678",			// Recipient
	"Enter message to send here",	// Message
	"NewSendname"			// Sendname to use for this message only.
);
var_dump($is_send); // bool
```

Set a new default sendname:
```php
$is_send = $client->setSendname("NewSendname");
var_dump($is_send); // bool
```