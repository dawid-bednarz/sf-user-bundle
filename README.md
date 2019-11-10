# DESCRIPTION
CRUD User
# INSTALLATION
`composer require dawid-bednarz/sf-user-bundle`

#### 1. Create user_bundle.yaml in your ~/config/packages directory
```yaml
dawbed_user_bundle:
   entities: # overwrite entities
      DawBed\UserBundle\Entity\AbstractUser: App\Entity\User\User
      DawBed\UserBundle\Entity\AbstractUserStatus: App\Entity\User\UserStatus
      DawBed\UserBundle\Entity\AbstractUserToken: App\Entity\User\UserToken
   password:
      min_length: 14
      algorithm: !php/const PASSWORD_ARGON2I
```
#### 2 Import routes
```yaml
UserBundle:
  resource: '@UserBundle/Resources/config/routes.yaml'
 
# POST users - Create user
# PUT users​/{id} - Update user
# GET users​/{id} - Get user
# GET ​users - List users
# GET users​/statuses - List available user status
# DELETE ​users​/{id} - Delete user
# POST users​/change-password - Change user password
```

#### 3 Register Listener (config/services.yaml)
```yaml
       tags:
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::ERROR_RESPONSE }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::CREATE_RESPONSE }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::UPDATE_RESPONSE }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::DELETE_ITEM_RESPONSE }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::CHANGE_PASSWORD }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::CHANGED_PASSWORD }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::GET_ITEM_RESPONSE }
         - { name: kernel.event_listener, event: !php/const DawBed\UserBundle\Event\Events::LIST_RESPONSE }
```
# COMMANDS

`bin/console dawbed:debug:event_listener` - Checking if you have all registered listeners( it's auto run while clear cache )

