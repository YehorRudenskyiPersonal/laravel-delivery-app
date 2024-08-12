# Laravel Delivery Service

## Overview

A Laravel application to integrate with various courier services, including Nova Poshta, to handle package deliveries. This project includes functionality for sending delivery requests and retrying failed requests. In the future it's possible to extend the functionality with the new delivery services, add the new data to existing services, add the notifications by email, sms and so on, create API to manipulate stored deliveries and based on it do some extra actions (generate pdf for example).

## General application flow

We have a route to send the request to the service `/api/send`. It's  By default it will send the request to the Nova Poshta, specified in the task description. But the crusual thing is to add more services in the future. For this needs we have a config file `config/couriers.php`. In this file we can easily add the new delivery services. Service setup looks like this:

```php 
'nova_poshta' => [
    'endpoint' => env('NOVA_POSHTA_ENDPOINT', 'https://novaposhta.test/api/delivery'),
    'sender_address' => env('NOVA_POSHTA_SENDER_ADDRESS', 'Some address'),
    'adapter' => NovaPoshtaAdapter::class,
]
```
`endpoint` - api endpoint to send request
`sender_address` - address of the service, in the task specified that it's in the setting
`adapter` - Important thing is `adapter` property. Every delivery service can use theire own data format to pass. So we need an `Adapter` to adapt our incoming data, specified in the task to the service needs.

So here we have an answer to the question "How will the code change if there are 15 couriers?" - extra services will appear in the config and maybe extra adapters will be created, because adapters made the way that if data format is equal we can use existing adapter for the new service. Factory will take the other work.

The second question is "If the customer has a problem with the delivery of orders. Customer sends data, but courier service support says they are not receiving data from current service." - the answer is writing to db and queues. And logs for sure! Database is not specified in the task, so I made this writing optional too, but database is setted up in docker-compose. To allow writing to the database in ENV ` WRITE_TO_THE_DATABASE` needs to be setted to `true`. In this case after sending the request to the courier service endpoint and getting the response, data will be prepared and send to the `Job`. Job is a solution for the future if some large queries will appear. `RabbitMQ` used for the queues. If the Job will be failed we could use the failed queue to run it again. 

When Job will be done, Event will be triggered. Now the Event is writing the log, that Job was done and delivery was recorded, but in the future we could use it for Notifications, for example. Architecture is prepared, `Notification` class created and base setup was done. Code for notification is plased in the event and commented. 

If DB writing not allowed, we will still have the logs with the data if something will be wrong with the request.

But what if we are using DB writing and we got an information that some request was failed? We could want to resend the request automatically. And we can do it! With the `artisan console command`. Even failed requests going to be written to DB. And if we are sure that service was unavailable and that's the reason we could resend requests even after a long time. 

```bash
docker-compose exec app php artisan app:resend-failed-requests
```

There are extra parameters for this command: 
`service` - resend all failed requests for the specified service
`userEmail` - find specific user by email and resend

To test the command I prepared a `Seeder`. Run
```bash
docker-compose exec app php artisan db:seed
```
and after this the command to resend. You will see how it's working. 

And there are 2 unit tests for Factory and Adapter.

For the future API Controller was added, routes were added and commented. Laravel passport installed and settings prepared.

## Prerequisites

- **Docker**: Ensure Docker and Docker Compose are installed.
- **PHP**: PHP 8.3 is required.
- **Composer**: Used for managing PHP dependencies.

## Setup

1. **Clone this Repository**
    ```bash
    git clone https://github.com/YehorRudenskyiPersonal/laravel-delivery-app.git
    ```
2. **Use the command to decrypt env file**
    ```bash
    php artisan env:decrypt --key=3UVsEgGVK36XN82KKeyLFMhvosbZN1aF
    ```
    Key is the test one from documentation
3. **Build and run application**
    ```bash
    docker-compose build && docker-compose up
    ```
4. **Run Migrations in app container**
    ```bash
    docker-compose exec app php artisan migrate
    ```
5. **Add the queue**
    ```bash
    docker-compose exec rabbitmq rabbitmqadmin declare queue name=default durable=true
    ```
6. **Start the queue worker to process jobs**
    ```bash
    docker-compose exec app php artisan queue:work
    ```


## API Endpoint

URL: `/api/send`
Method: POST
Request Payload:
```json
{
  "package": {
    "width": 30.5,
    "height": 20.0,
    "length": 15.0,
    "weight": 5.5
  },
  "recipient": {
    "name": "Hey Ho",
    "phone": "+1234567890",
    "email": "john.doe@example.com",
    "address": "123 Main Street, Anytown, Anystate, 12345"
  },
  "service": "test"
}
```
`service` is an optional field for now, because in the task we have only 1 service. But for the future this field is important and may be required. For now if it's not specified value will be taken from ENV `DEFAULT_COURIER_SERVICE`

Response:

    Success: 200 OK with a confirmation message.
    Error: Appropriate error messages for validation or processing issues.

## Testing

1. Open Postman and create a new request.

2. Set Method to POST and URL to `http://localhost/api/send`.

3. Go to the Body tab, select raw and JSON from the dropdown.

4. Enter the JSON Payload:
    ```json
    {
    "package": {
        "width": 30.5,
        "height": 20.0,
        "length": 15.0,
        "weight": 5.5
    },
    "recipient": {
        "name": "Hey Ho",
        "phone": "+1234567890",
        "email": "john.doe@example.com",
        "address": "123 Main Street, Anytown, Anystate, 12345"
    },
    "service": "test"
    }
    ```

5. Click "Send" to make the request and observe the response.

6. Check logs or database directly to see if all was done good.

## Running Tests

To run tests using PHPUnit, use the following command:
```bash
docker-compose exec app php artisan test
```

Ensure that all tests pass.
