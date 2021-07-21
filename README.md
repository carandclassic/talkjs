# TalkJS PHP SDK

[![Latest version](https://img.shields.io/github/release/CarAndClassic/talkjs.svg?style=flat-square)](https://github.com/CarAndClassic/talkjs/releases)
[![Total downloads](https://img.shields.io/packagist/dt/CarAndClassic/talkjs.svg?style=flat-square)](https://packagist.org/packages/CarAndClassic/talkjs)

Forked from [shapintv/talkjs](https://github.com/shapintv/talkjs).

For more information on parameters, custom data, and other things you can send with this API, please see the [TalkJS REST API documentation](https://talkjs.com/docs/Reference/REST_API/Getting_Started/Introduction.html).

The structure of this package is crafted around the TalkJS REST API structure, and as such all the following are treated as distinct APIs:

- Users
- Conversations
- Messages

Not implemented currently:

- Notifications
- Sending file Messages

## Install

Via Composer

``` bash
$ composer require carandclassic/talkjs
```

## Usage

This can be used as-is or with auto discovery in Laravel.

### Create a `TalkJSClient`

```php
use CarAndClassic\TalkJS\TalkJSClient;

$appId = 'my_app_id';
$secretKey = 'my_secret_key';
$talkJSClient = new TalkJSClient($appId, $secretKey);
```

### Laravel

Firstly, add `TALKJS_APP_ID` and `TALKJS_SECRET_KEY` to your env file. These are pulled in from the package's config automatically `talkjs.app_id` and `talkjs.secret_key` respectively.

If you'd like to change this, you can publish the application config and modify the `talkjs.php` config file in your application:
```
php artisan vendor:publish --provider=CarAndClassic\\TalkJS\\Providers\\TalkJSServiceProvider
```

Laravel's automatic service discovery will let you dependency inject `TalkJSClient` as per normal. Alternatively for one-off use you can also pass overriding `appId` and `secretKey` arguments using `app()->make()`:

```php
$talkJSClient = app()->make(TalkJSClient::class, ['appId' => 'my_custom_app_id', 'secretKey' => 'my_custom_secret_key']);
```

### Input vs API data

Below you'll see "input data" and "API data" referenced.
- Input data = data you sent via this package
- API data = data returned from the API

This is done because currently TalkJS returns empty 200 responses for successful resource update/creation, but it's still helpful to return what you've sent along.

### IDs

TalkJS IDs for users and conversations are custom and managed by your application.

### Filtering

All endpoints that fetch multiple records (users, conversations, messages) have limit & pagination options. API usage below will use a `$filters` variable where possible for demonstration, and it will look like this:

```php
$filters = [
    'limit' => 50,
    'startingAfter' => 'latestMessageId'
];
```

### Creating a TalkJSClient

```php
$appId = 'YOUR_APP_ID';
$secretKey = 'YOUR_SECRET_KEY';
$talkJSClient = new TalkJSClient($appId, $secretKey);
```

### Users

Please note TalkJS currently does not offer a user deletion API, and instead [recommend](https://talkjs.com/dashboard/tLjeWrEK/docs/Reference/REST_API/Users.html#page_Deleting-users) you use the update/edit endpoints to anonymise personally identifiable information.

- Creating or updating a user, returns `UserCreated` class with input data
```php
$talkJSClient->users->createOrUpdate('my_custom_id', [
    "name" => "Alice",
    "email" => ["alice@example.com"],
    "welcomeMessage" => "Welcome!",
    "photoUrl" => "https =>//demo.talkjs.com/img/alice.jpg",
    "role" => "buyer",
    "phone" => ["+1123456789"],
    "custom" => [
        "foo" => "bar"
    ]
]);
```

- Retrieve a user, returns a `User` model class with API data
```php
$talkJSClient->users->find('my_user_id');
```

- Get all users, returns an array of `User` model class with API data
```php
$talkJSClient->users->find($filters);
```

- Get user's conversations, returns an array of `Conversation` model class with API data
```php
$talkJSClient->users->getConversations('my_user_id');
```

### Conversations

- Create or update a conversation, returns a ConversationCreatedOrUpdated event class with input data
```php
$talkJSClient->conversations->createOrUpdate('my_conversation_id', [
    'subject' => 'My new conversation',
    'participants' => ['my_user_id_1', 'my_user_id_2'],
    'welcomeMessages' => ['Welcome!'],
    'custom' => ['test' => 'test'],
    'photoUrl' => null
]);
```

- Retrive a conversation, returns a Conversation model class with API data
```php
$talkJSClient->conversations->get('my_conversation_id');
```

- Find conversations, returns an array of `Conversation` model class with API data
```php
$talkJSClient->conversations->find();
```

- Join a conversation, returns a `ConversationJoined` event class with input data
```php
$talkJSClient->conversations->join('my_conversation_id', 'my_user_id');
```

- Leave a conversation, returns a `ConversationLeft` event class with input data
```php
$talkJSClient->conversations->leave('my_conversation_id', 'my_user_id');
```

- Delete a conversation, returns a `ConversationLeft` event class with input data
```php
$talkJSClient->conversations->delete('my_conversation_id');
```

- Update participation settings (notifications and read/write access)
```php
$notify = true; // Boolean, default true
$access = 'ReadWrite'; // ReadWrite or Read, default ReadWrite
$talkJSClient->conversations->updateParticipation('my_conversation_id', 'my_user_id', $notify, $access);
```

### Messages

For more information on custom data and filters, please refer to the TalkJS documentation linked above.

Please note:
- Sending file attachment is not yet implemented.
- Endpoints that return multiple messages will return them in descending order, i.e. latest first.

```php
$custom = [
  'foo' => 'bar'
];
```

- Get messages in a conversation, returns an array of `Message` model class with API data
```php
$talkJSClient->messages->get('my_conversation_id', $filters);
```

- Find specific message in a conversation
```php
$talkJSClient->messages->find('my_conversation_id', 'message_id');
```

- Post a system message, returns a `MessageCreated` event class with input data and `type` of `SystemMessage`
```php
$talkJSClient->messages->postSystemMessage('my_conversation_id', $text, $custom);
```

- Post a user message, returns a `MessageCreated` event class with input data and `type` of `UserMessage`
```php
$talkJSClient->messages->postUserMessage('my_conversation_id', $username, $text, $custom);
```

- Edit a message, returns a `MessageEdited` event class with input data
```php
$talkJSClient->messages->edit('my_conversation_id', 'message_id', $text, $custom);
```

- Delete a message, returns a `MessageDeleted` event class with no data
```php
$talkJSClient->messages->delete('my_conversation_id', 'message_id');
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
