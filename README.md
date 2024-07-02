# Basiq.io PHP SDK

This is the documentation for the PHP SDK for Basiq.io API

## Introduction

Basiq.io PHP SDK is a set of tools you can use to easily communicate with Basiq API.
If you want to get familiar with the API docs, [click here](https://basiq.io/api/).

The SDK is organized to mirror the HTTP API's functionality and hierarchy.
The top level object needed for SDKs functionality is the Session
object which requires your API key to be instantiated.
You can grab your API key on the [dashboard](http://dashboard.basiq.io).

## Changelog

1.1.0 Added support for secondaryLoginId

0.9.1beta - getTransactions now receives limit parameter. Fixed bug on refresh all connections

0.9.0beta - Initial release

## Getting started

Now that you have your API key, you can use the following command to install the SDK:

```bash
composer require basiqio/basiq-sdk-php
```

Next step is to import the used classes into your namespace.
A list of classes you will probably use the most:
```php
// Used to handle the token session
use Basiq\Session;

// Used to manipulate jobs and connections
use Basiq\Services\ConnectionService;

// Used to manipulate users
use Basiq\Services\UserService;
```

## Common usage examples

### Fetching a list of institutions

You can fetch a list of supported financial institutions. The function returns a list of Institution structs.

```php
use Basiq\Session;

$session = new Session("YOUR_API_KEY");

$institutions = $session->getInstitutions();
```

You can specify the version of API when instantiating Session object. When the version is not specified, default version is 1.0.

```php
use Basiq\Session;

$session = new Session("YOUR_API_KEY", "2.0");

$institutions = $session->getInstitutions();
```

### Creating a new connection

When a new connection request is made, the server will create a job that will link user's financial institution with your app.

```php
use Basiq\Session;

$session = new Session("YOUR_API_KEY");

$user = $session->forUser($userId);

$job = $user->createConnection($institutionId, $userId, $password[, $securityCode, $secondaryLoginId]);

// Poll our server to wait for the credentials step to be evaluated
$connection = job->waitForCredentials(1000, 60);
```

### Fetching and iterating through transactions

In this example, the function returns a transactions list struct which is filtered by the connection->id property. You can iterate
through transactions list by calling Next().

```php
use Basiq\Session;
use Basiq\Utilities\FilterBuilder;

$session = new Session("YOUR_API_KEY");

$user = $session->forUser($userId);

$fb = new FilterBuilder();
$fb->eq("connection->id", "conn-id-213-id");
$transactions = $user->getTransactions($fb);


while ($transactions->next()) {
        var_dump("Next transactions len:", len(transactions.Data))
}
```

## API

The API of the SDK is manipulated using Services and Entities. Different
services return different entities, but the mapping is not one to one.

### Errors

If an action encounters an error, you will receive an HTTPResponseException
instance. The class contains all available data which you can use to act
accordingly.

##### HTTPResponseException class fields
```php
public $response;
public $statusCode;
public $message;
```

Check the [docs](https://basiq.io/api/) for more information about relevant
fields in the error object.

### Filtering

Some of the methods support adding filters to them. The filters are created
using the FilterBuilder class. After instantiating the class, you can invoke
methods in the form of comparison(field, value).

Example:
```php
use Basiq\Utilities\FilterBuilder;

$fb = new FilterBuilder();
$fb->eq("connection->id", "conn-id-213-id")->gt("transaction.postDate", "2018-01-01")
$transactions = $user->getTransactions(fb);
```

This example filter for transactions will match all transactions for the connection
with the id of "conn-id-213-id" and that are newer than "2018-01-01". All you have
to do is pass the filter instance when you want to use it.

### SDK API List

<details>
<summary>
Services
</summary>

#### Session

##### Creating a new Session object

```php
$session = new Session("YOUR_API_KEY");
```

#### UserService

The following are APIs available for the User service

##### Creating a new UserService

```php
$userService = new UserService($session);
```

##### Referencing a user
*Note: The following action will not send an HTTP request, and can be used
to perform additional actions for the instantiated user.*

```php
$user = $userService->forUser($userId);
```

##### Creating a new User

```php
$user = $userService->create(["email" => "", "mobile" => ""]);
```

##### Getting a User

```php
$user = $userService->get($userId);
```

##### Update a User

```php
$user = $userService->update($userId, ["email" => "", "mobile" => ""]);
```

##### Delete a User

```php
null = $userService->delete($userId);
```

##### Refresh connections

```php
$jobs = $userService->refreshAllConnections($userId);
```

##### List all connections

```php
$conns = $userService->getAllConnections($userId[, $filter]);
```

##### Get account

```php
$acc = $userService->getAccount($userId, $accountId);
```

##### Get accounts

```php
$accs = $userService->getAccounts($userId[, $filter]);
```

##### Get transaction

```php
$transaction = $userService->getTransaction($userId, $transactionId);
```

##### Get transactions

```php
$transactions = $userService->getTransactions($userId[, $filter]);
```

#### ConnectionService

The following are APIs available for the Connection service

##### Creating a new ConnectionService

```php
$connService = new ConnectionService($session, $user);
```

##### Get connection

```php
$connection = $connService->get($connectionId);
```

##### Get connection entity with ID without performing an http request

```php
$connection = $connService->for($connectionId);
```

##### Create a new connection

```php
$job = $connService->create(["institutionId" => "", "loginId" => "", "password" => "", "securityCode" => "", "secondaryLoginId" => ""]);
```

##### Update connection

```php
$job = $connService->update($connectionId, $password);
```

##### Delete connection

```php
null = $connService->delete($connectionId);
```

##### Get a job

```php
$job = $connService->getJob($jobId);
```

</details>


<details><summary>
Entities
</summary>

##### Updating a user instance

```php
$user = $user->update(["email" => "", "mobile" => ""]);
```

##### Deleting a user

```php
null = $user->delete();
```

##### Get all of the user's accounts

```php
$accounts = $user->getAccounts();
```

##### Get a user's single account

```php
$account = $user->getAccount($accountId);
```

##### Get all of the user's transactions

```php
$transactions = $user->getTransactions($filterBuilder = null, $limit = null < 500);
```

##### Get a user's single transaction

```php
$transaction = $user->getTransaction($transactionId);
```

##### Create a new connection

```php
$job = $user->createConnection(["institutionId" => "", "loginId" => "", "password" => "", "securityCode" => "", "secondaryLoginId" => ""]);
```

##### Refresh all connections

```php
$jobs = $user->refreshAllConnections();
```

#### Connection

##### Refresh a connection

```php
$job = $connection->refresh();
```

##### Update a connection

```php
$job = $connection->update($password);
```

##### Delete a connection

```php
null = $connection->delete();
```

#### Job

##### Get the connection id (if available)

```php
$connectionId = $job->getConnectionId();
```

##### Get the connection

```php
$connection = $job->getConnection();
```

##### Get the connection after waiting for credentials step resolution
(interval is in milliseconds, timeout is in seconds; in case of timeout
an exception will be thrown)

```php
$connection = $job->waitForCredentials($interval, $timeout);
```

##### Get the connection after waiting for transactions step resolution
(interval is in milliseconds, timeout is in seconds; in case of timeout
an exception will be thrown)

```php
$connection = $job->waitForTransactions($interval, $timeout);
```

#### Transaction list

##### Getting the next set of transactions [mut]

```php
$next = $transactions->next();
```
</details>
