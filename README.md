# StopForumSpam API Client

Documentation: [stopforumspam.com/usage](https://stopforumspam.com/usage)

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

$result = $client->request
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
