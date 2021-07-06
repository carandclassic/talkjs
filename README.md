# TalkJS PHP SDK

[![Latest version](https://img.shields.io/github/release/CarAndClassic/talkjs.svg?style=flat-square)](https://github.com/CarAndClassic/talkjs/releases)
[![Total downloads](https://img.shields.io/packagist/dt/CarAndClassic/talkjs.svg?style=flat-square)](https://packagist.org/packages/CarAndClassic/talkjs)

Forked from [shapintv/talkjs](https://github.com/shapintv/talkjs).

For more information on parameters, custom data, and other things you can send with this API, please see the [TalkJS REST API documentation](https://talkjs.com/docs/Reference/REST_API/Getting_Started/Introduction.html).

The structure of this package is crafted around the TalkJS REST API structure, and as such all the following are treated as distinct APIs:

- Users
- Conversations
- Messages
- Participations

Not implemented currently:

- Notifications
- Sending file Messages

## Install

Via Composer

``` bash
$ composer require carandclassic/talkjs
```

## Usage

### Create a `TalkJSClient`

```php
use CarAndClassic\TalkJS\TalkJSClient;

$appId = 'your App ID';
$secretKey = 'your secret key';
$talkJSClient = new TalkJSClient($appId, $secretKey);
```

### Users

```php
// Create or update a user
$talkJsClient->userApi->createOrUpdate('my_custom_id', [
    'email' => 'georges@abitbol.com',
]);

// Retrieve a user
$user = $talkJsClient->userApi->get('my_custom_id');
```

Please note TalkJS currently does not offer a user deletion API, and instead [recommend](https://talkjs.com/dashboard/tLjeWrEK/docs/Reference/REST_API/Users.html#page_Deleting-users) you use the update/edit endpoints to anonymise personally identifiable information. 

### Conversations

```php
// Create or update a conversation
$talkJsClient->conversationApi->createOrUpdate('my_custom_id', [
    'subject' => 'My new conversation',
]);

// Retrive a conversation
$conversation = $talkJsClient->conversationApi->get('my_custom_id');

// Find conversations
$conversations = $talkJsClient->conversationApi->find();

// Join a conversation
$talkJsClient->conversationApi->join('my_conversation_id', 'my_user_id');

// Leave a conversation
$talkJsClient->conversationApi->leave('my_conversation_id', 'my_user_id');
```

### Messages

For more information on custom data and filters, please refer to the TalkJS documentation linked above.

```php
$custom = [
  // custom TalkJS data
];

// Post a system message
$talkJsClient->messageApi->postSystemMessage($conversationId, $message, $custom);

// Post a user message
$talkJsClient->messageApi->postUserMessage($conversationId, $username, $message, $custom);

// Find messages in a conversation
$filters = [
    'limit' => 50,
    'startingAfter' => 'latestMessageId'
];
$talkJsClient->messageApi->findMessages($conversationId, $filters);
```

### Integration with symfony

Create a new HttpClient:

```yml
framework:
    http_client:
        scoped_clients:
            talkjs.client:
                auth_bearer: '%env(TALKJS_SECRET_KEY)%'
                base_uri: 'https://api.talkjs.com/v1/%env(TALKJS_APP_ID)%/'
                headers:
                    'Content-Type': 'application/json'
```

Then create your service:

```yml
services:
    CarAndClassic\TalkJS\TalkJSClient: ~
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
