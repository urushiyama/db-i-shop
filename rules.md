# Coding rules

## Naming convention

### General

- The first character of a file which clients could see should not be `\_`.
- The first character of a file which clients could not see should be `\_`.

- PHP files which consists of class definition should be named `\_C\_<ClassName>.php`.

## View

- The name of each file should end with `-page.php` or `-container.php`.
- The name of each file which makes a view should end with `-page.php`.

## Protocol rules

- View should be defined by 'p' parameter on __GET__ method.
- Action should be defined by 'a' parameter on __POST__ method.

### valid actions

| page from      | page to | action           | ActionDispatcher function |
|:---------------|:--------|:-----------------|:--------------------------|
| register       | NULL    | register-account | registerAccount           |
| login          | NULL    | login-account    | loginAccount              |
| logout         | top     | logout-account   | logoutAccount             |
| delete-account | top     | delete-account   | deleteAccount             |
