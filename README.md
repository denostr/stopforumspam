# StopForumSpam API Client

Documentation: [stopforumspam.com/usage](https://stopforumspam.com/usage)

[![Latest Stable Version](https://poser.pugx.org/denostr/stopforumspam/v/stable)](https://packagist.org/packages/denostr/stopforumspam)
[![Total Downloads](https://poser.pugx.org/denostr/stopforumspam/downloads)](https://packagist.org/packages/denostr/stopforumspam)
[![Latest Unstable Version](https://poser.pugx.org/denostr/stopforumspam/v/unstable)](https://packagist.org/packages/denostr/stopforumspam)
[![License](https://poser.pugx.org/denostr/stopforumspam/license)](https://packagist.org/packages/denostr/stopforumspam)

## Installation

The preferred way to install this extension is through [composer](https://getcomposer.org/).

```
php composer.phar require denostr/stopforumspam
```


or add

```
"denostr/stopforumspam": "~0.1.0"
```

to the `require` section of your `composer.json` file.

## Usage

```
$client = new denostr\stopforumspam\Client();

// Set IP for check
$client->ip('1.2.3.4');

// Set email for check
$client->email('mail@example.com')

// Set response JSON format
$client->format(denostr\stopforumspam\Client::FORMAT_JSON);

$result = $client->request();
```

#### Set multiple params

```
$client->ip(['1.2.3.4', '4.6.7.8', '9.10.11.12']);
$client->email(['first@example.com', 'second@example.com']);
```

#### Debug mode

```
$client->debug(true);
```

#### Wildcards

```
$client->nobademail(true);
```
