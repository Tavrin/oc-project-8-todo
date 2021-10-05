# Authentication Guide

The authentication process is set inside the security configuration file (security.yaml) inside the config folder.
The official documentation can be found [here](https://symfony.com/doc/4.4/security.html).

The User entity is used for authentication.

## The encoder
The encoder sets the algorithm to be used for the password hashing, it needs to be set to auto to use
the most recent and secure algorithm available automatically. 

```yaml
# config/packages/security.yaml
encoders:
  App\Entity\User:
    algorithm: auto
```

## The User provider
The user provider is used for optional features like remember me and impersonation. The set property is the one used for login in, in our case it is set to the username.
```yaml
# config/packages/security.yaml
providers:
  app_user_provider:
    entity:
      class: App\Entity\User
      property: username
```

## The firewall

The firewall is the part of the security configuration that will manage what parts of the websites are accessible with or without authentication.
It also configures how the authentication process will be handled. In our case, a guard authenticator was implemented.
```yaml
# config/packages/security.yaml
firewalls:
  dev:
    pattern: ^/(_(profiler|wdt)|css|images|js)/
    security: false

  main:
    anonymous: ~
    pattern: ^/
    guard:
      authenticators:
        - App\Security\LoginFormAuthenticator
    logout:
      path: logout
```

The login process is managed by the LoginFormAuthenticator class, which can be found in src/Security.

## The access control

Finally, the access control fine tunes the authorization needed to access certain paths, for example
some paths can be made accessible to any user or only to admins users.
```yaml
# config/packages/security.yaml
access_control:
  - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
  - { path: ^/users, roles: ROLE_ADMIN }
  - { path: ^/, roles: ROLE_USER }
```