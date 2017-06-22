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

| page from        | page to         | action           | ActionDispatcher function |
|:-----------------|:----------------|:-----------------|:--------------------------|
| register         | NULL            | register-account | registerAccount           |
| login            | NULL            | login-account    | loginAccount              |
| register-update  | NULL            | update-account   | updateAccount             |
| logout           | top             | logout-account   | logoutAccount             |
| delete-account   | top             | delete-account   | deleteAccount             |
| product-register | NULL            | update-product   | updateProduct             |
| product-detail   | shopping-basket | add-to-cart      | addToCart                 |
| edit-product     | product-register\|manage-product | edit-product (submit[update\|delete]) | editProduct\|deleteProduct |

## Exception rules

- Do __NOT *throw*__ instance of ApplicationExceptions in functions nor methods.
  In functions and methods you only have to call `ApplicationException::create(const $exception)`.

- You __CANNOT__ create new instance of ApplicationExceptions with calling `new ApplicationException(...)`.
  Store new instance with calling `ApplicationException::create(const $exception)`.

- If you want to throw exceptions you stored, call `ApplicationException::raise()`.
  This method throws all exceptions you stored by calling `ApplicationException::create(const $exception)`.
  After calling this method, all stored exceptions will be removed.

- If you want to remove stored exceptions, call `ApplicationException::reset()`.

### Exception id rules

- Exception id is a integer number which has 4 digits.
- The highest digit shows Exception's alert level.
  - `info` level is numbered 0~0999 (The highest digit is '0').
  - `warn` level is numbered 1000~1999 (The highest digit is '1').
  - `danger` level is numbered 2000~2999 (The highest digit is '2').

- The second & third highest digit shows exception source.
  - '01' means user name.
  - '02' means password.
  - '03' means password-confirmation.
  - '92' means login function.
  - '99' means whole application.

- The lowest digit is identifier, which makes each exception id unique.
  - Generally 0~4 is used for common exceptions and 5~9 is used for specific exceptions to exception source.
