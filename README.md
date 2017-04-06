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

#### Get your current creditcount:
```php
$credits = $client->getCredits();
var_dump($credits); // int
```

#### Get current sendname:
```php
$sendname = $client->getSendname();
var_dump($sendname); // string
```

#### Get messages send:
```php
$history = $client->getHistory();
var_dump($history); // array
```

#### Send a message to a single recipient:
```php
$messageinfo = $client->send(
	"0612345678",			// Single recipient 
	"Enter message to send here"	// Message to send
);
var_dump($messageinfo); // array
var_dump($messageinfo["messageid"]); // int
```
__Note:__ Make sure to define the recipients as strings, as PHP might handle the number as an integer otherwise!

#### Send a message to multiple recipients (max 50):
```php
$messageinfo = $client->send(
	["0612345678","0687654321"],	// Array of recipients
	"Enter message to send here"	// Message to send
);
var_dump($messageinfo); // array
```

#### Send a message with a different sendname than the default one stored:
```php
$messageinfo = $client->send(
	"0612345678",			// Recipient
	"Enter message to send here",	// Message
	"NewSendname"			// Sendname to use for this message only.
);
var_dump($messageinfo); // array
```

#### Set a new default sendname:
```php
$is_set = $client->setSendname("NewSendname");
var_dump($is_set); // bool
```

Testmode
-----
The client supports a testmode, so the functionality can be tested without actually sending messages by using the `isTestmode()` method:
```php
$messageinfo = $client->isTestmode()->send(
	"0612345678",			// Single recipient 
	"Enter message to send here"	// Message to send
);
var_dump($messageinfo); // array
var_dump($messageinfo["messageid"]); // int (random)
```
The messageid returned from a testmode request is a random number and should therefor not be stored. This is because the API server isn't actually processing the message.

Error handling
-----
The client throws an `Exception` whenever something is wrong with the request. When invoking the `send()` method, the client might also throw a `CreditsException`. This will only happen if the account does not have enough credits to execute the `send()` method.

```php
// Include composers autoloader
include "vendor/autoload.php";
// Setup try block
try
{
	// Create the object
	$client = new \Zeauw\SSCAPIv2\Client("ENTER_YOUR_ACCESS_TOKEN_HERE");
	// Send a message
	$messageinfo = $client->send(
		"0612345678",
		"Message to send"
	);
	var_dump($messageinfo); // array
}
catch (\Zeauw\SSCAPIv2\Exceptions\CreditsException $e)
{
	// The user does not have enought credits.
	var_dump($e->getMessage()); // Readable message
	var_dump($e->getRemainingCredits()); // int. Remaining credits within the users account.
	var_dump($e->getRequiredCredits()); // int. Required amount of credits for the last request.
}
catch (\Exception $e)
{
	// An error occurred
	var_dump($e->getMessage()); // Readable message
}
```

Throttling
-----
Usage of the SMS Service Center API (v2) is unlimited. To prevent fraud and abuse, and to secure the server performance, requests to the API are throttled. You can make up to 150 requests each 5 minutes.

The API will respond with a __429 Too many requests__ reponse, whenever the limit has been reached. 