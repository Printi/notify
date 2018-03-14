Notify - Priority Messaging
==========================

Notify is messaging service for Omega2. which responsible for sending message
to Alpha or AWS sns based on priority.

## Installing Notify as Bundle

The recommended way to install ApiClient is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of Notify:

```bash
php composer.phar require printi/notify
```

You can then later update notify using composer:

 ```bash
composer.phar update printi/notify
```

## User Guide


Basic notify configuration:

For eg:
```yaml
notify:
    transition:
        send_to_prepress: high
        prepress_reject: low
        prepress_reject_failed: high
        prepress_approve: high
        send_to_production: high
        waiting_for_upload: high
        new_upload: low
        cancel: high
        finish: high
``` 
This configuration is not mandatory for our application. we can override above configuration 
by creating ``notify.yaml`` yaml file under ``/config/packages`` folder.


## How to use

We can inject Notify as a service into our application.

for eg:
```php

namespace App;
use Printi\NotifyBundle\Notify;

class HelloClass {

    private $notify;
    
    public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }
    
    public function onTransitionUpdate()
    {
        $message = [
            "order_item_id" => 11111,
            "transition"    => 'prepress_reject',
            "reference"     => null,
            "status_id"     => 50,
            "version"       => 2,
        ];
        $this->notify->notifyOnTransition('prepress_reject', $message);
    }
}
```