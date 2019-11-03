# DESCRIPTION
This is logic responsible for create user without context uses.
# INSTALLATION
`composer require dawid-bednarz/sf-user-bundle`

####1. Create entities file
```php
namespace App\Entity\User;

use DawBed\PHPUser\User as Base;

class User extends Base
{
}
```
```php
namespace App\Entity\User;

use DawBed\PHPUser\UserStatus as Base
class UserStatus extends Base
{
}
```
#### 2. Create user_bundle.yaml in your ~/config/packages directory
```yaml
dawbed_user_bundle:
    entities:
      User: 'App\Entity\User\User'
      UserStatus: 'App\Entity\User\UserStatus'
   password:
      auto_generate: true
      min_length: 8
      algorithm: !php/const PASSWORD_ARGON2I
```
#### 3 Register Listener (config/services.yaml)
```yaml
    App\EventListener\User\Registration\ErrorListener:
       tags:
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::ERROR_RESPONSE }
    App\EventListener\User\Crud\WriteListener:
       tags:
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::CREATE_RESPONSE }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::UPDATE_RESPONSE }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::DELETE_ITEM_RESPONSE }
    App\EventListener\User\Crud\GetItemListener:
       tags:
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::GET_ITEM_RESPONSE }
    App\EventListener\User\Crud\ListItemListener:
       tags:
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::LIST_RESPONSE }
```
# COMMANDS
Checking if you have all registered listeners
```
bin/console dawbed:debug:event_listener  
```
