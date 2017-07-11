<?php
require_once '_C_SessionController.php';
require_once '_C_Renderer.php';
require_once '_C_ApplicationException.php';
require_once '_C_Members.php';
require_once '_C_Dealers.php';
require_once '_C_Products.php';
require_once '_C_PurchasedProducts.php';
require_once '_C_DeliveryTypes.php';
require_once '_C_Crypt.php';
require_once '_C_Mailers.php';

class ActionDispatcher {

  static $action_table = [
    'register-account'=>'registerAccount',
    'login-account'=>'loginAccount',
    'update-account'=>'updateAccount',
    'logout-account'=>'logoutAccount',
    'delete-account'=>'deleteAccount',
    'search-account'=>'searchAccount',
    'search-product'=>'searchProduct',
    'update-product'=>'updateProduct',
    'add-to-cart'=>'addToCart',
    'remove-from-cart'=>'removeFromCart',
    'purchase-product'=>'purchaseProduct',
    'edit-product'=>'editProduct',
    'send-register-email'=>'sendRegisterEmail'
  ];

  static function act(MainController $con) {
    if (array_key_exists($con->action, self::$action_table)) {
      $func = self::$action_table[$con->action];
      self::$func($con);
    } else {
      ApplicationException::create(ApplicationException::FATAL_ERROR);
    }
    if (ApplicationException::isStored()) {
      ApplicationException::raise();
    }
  }

  static function registerAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
    #   - password-confirmation : password
    #   - login_type : hidden
    if (!isset($_POST['user_name'])
        || !isset($_POST['password'])
        || !isset($_POST['password-confirmation'])
        || !isset($_POST['login_type'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'register';
      return false;
    }
    if (!array_key_exists($_POST['login_type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'register';
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_POST['login_type']]['model'];
    if ($model == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    $model::validateValues([
      'name'=>$_POST['user_name'],
      'password'=>$_POST['password'],
      'confirmation'=>$_POST['password-confirmation']
    ]);
    $user = $model::find_by(['name'=>$_POST['user_name']]);
    if ($user) {
      ApplicationException::create(ApplicationException::DUPLICATED_USER_NAME);
    }
    if (ApplicationException::isStored()) {
      $con->page = 'register';
      return false;
    }
    /* Succeed for registration */
    if (strlen($_POST['password']) < 6) {
      ApplicationException::create(ApplicationException::SHORT_PASSWORD);
    }
    $new = $model::create(['name'=>$_POST['user_name'], 'password'=>$_POST['password']]);
    SessionController::login($new, $_POST['login_type']);
    $con->page = 'top';
    return true;
  }

  static function loginAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
    #   - login_type : hidden
    if (!isset($_POST['user_name'])
        || !isset($_POST['password'])
        || !isset($_POST['login_type'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'login';
      return false;
    }
    if (!array_key_exists($_POST['login_type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'login';
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_POST['login_type']]['model'];
    if ($model == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    $user = $model::find_by(['name'=>$_POST['user_name']]);
    if (!$user) {
      ApplicationException::create(ApplicationException::INVALID_LOGIN_COMBINATION);
      $con->page = 'login';
      return false;
    }
    if (!$user->authenticate($_POST['password'])) {
      ApplicationException::create(ApplicationException::INVALID_LOGIN_COMBINATION);
      $con->page = 'login';
      return false;
    }
    SessionController::login($user, $_POST['login_type']);
    $con->page = 'top';
    return true;
  }

  static function updateAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - old-password : password
    #   - password : password
    #   - password-confirmation : password
    if (!isset($_POST['user_name'])
        || !isset($_POST['old-password'])
        || !isset($_POST['password'])
        || !isset($_POST['password-confirmation'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'register-update';
      return false;
    }
    $user = SessionController::currentUser();
    if ($user == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    if (!$user->authenticate($_POST['old-password'])) {
      ApplicationException::create(ApplicationException::INVALID_PASSWORD);
      $con->page = 'register-update';
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_SESSION['login_type']]['model'];
    if ($model == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    $model::validateValues([
      'name'=>$_POST['user_name'],
      'password'=>$_POST['password'],
      'confirmation'=>$_POST['password-confirmation']
    ]);
    $registrated = $model::find_by(['name'=>$_POST['user_name']]);
    if ($registrated && $user->id !== $registrated->id) {
      ApplicationException::create(ApplicationException::DUPLICATED_USER_NAME);
    }
    if (ApplicationException::isStored()) {
      $con->page = 'register-update';
      return false;
    }
    $user->name = $_POST['user_name'];
    $user->setPassword($_POST['password']);
    $user->save();
    SessionController::login($user, $_SESSION['login_type']);
    $con->page = 'top';
    return true;
  }

  static function logoutAccount(MainController $con) {
    # - no params
    SessionController::logout();
    return true;
  }

  static function deleteAccount(MainController $con) {
    # - no params
    # ~~~~~~~~~~~~~~~~~~~~~~~~
    # - params
    #   - account-id
    #   - account-type
    # - only can do by admin
    if (isset($_POST['account-id']) || isset($_POST['account-type'])) {
      if (!isset($_POST['account-id']) || !isset($_POST['account-type'])) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        return false;
      }
      if (!array_key_exists($_POST['account-type'], SessionController::LOGIN_TYPE)) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        $con->page = 'manage-account';
        return false;
      }
      $user = SessionController::currentUser();
      if ($user == null || !($user->isAdmin())) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        return false;
      }
      // delete an account by admin
      $model = SessionController::LOGIN_TYPE[$_POST['account-type']]['model'];
      if ($model == null) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        return false;
      }
      $account = $model::find_by(['id'=>$_POST['account-id']]);
      if ($account == null) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        return false;
      }
      $account->destroy();
      $con->page = 'manage-account';
      return true;
    } else {
      $user = SessionController::currentUser();
      if ($user == null || $user->isAdmin()) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        return false;
      }
      $user->destroy();
      SessionController::destroy();
      return true;
    }
  }

  static function searchAccount(MainController $con) {
    # - params
    #   - account-type
    #   - query
    #       :if query == '' do index search
    #   - (start)
    #   - submit[search] || submit[index]
    if (!isset($_GET['account-type'])
        || !isset($_GET['query'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-account';
      return false;
    }
    if (!array_key_exists($_GET['account-type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-account';
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_GET['account-type']]['model'];
    $start = (isset($_GET['start'])) ? $_GET['start']: 1;
    $max = ModelBase::query("SELECT count(*) as count FROM ".$model::getTable())[0]['count'];
    $end = ($max > $start + 9) ? $start + 9 : $max;
    if ($_GET['query'] == '' || isset($_GET['submit']['index'])) {
      // return all accounts
      $sql= "SELECT id, name, password FROM (SELECT @row:=@row+1 as row, id, name, password FROM members, (select @row:=0) ini ORDER BY id) result WHERE row between :start and :end";
      $results = $model::query($sql, ['start'=>$start, 'end'=>$end]);
    } else {
      // return name matched accounts
      $query = str_replace('%', '\%', $_GET['query']);
      $sql= "SELECT id, name, password FROM (SELECT @row:=@row+1 as row, id, name, password FROM members, (select @row:=0) ini WHERE name like :name ORDER BY id) result WHERE row between :start and :end";
      $results = $model::query($sql, ['start'=>$start, 'end'=>$end, 'name'=>"%$query%"]);
    }
    $con->other_params['results'] = $results;
    $con->other_params['max'] = $max;
    $con->page = 'manage-account';
    return true;
  }

  static function sendRegisterEmail(MainController $con) {
    # - params
    #   - account-type
    #   - user-name
    #   - email
    # - only can do by admin
    $user = SessionController::currentUser();
    if ($user == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    if (!($user instanceof Members) || !($user->isAdmin())) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    if (!isset($_POST['user-name'])
        || !isset($_POST['email'])
        || !isset($_POST['account-type'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-account';
      return false;
    }
    if (!array_key_exists($_POST['account-type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-account';
      return false;
    }
    if (!Mailers::verify($_POST['email'])) {
      ApplicationException::create(ApplicationException::INVALID_EMAIL);
    }
    $password = Crypt::randomHexString(8);
    $model = SessionController::LOGIN_TYPE[$_POST['account-type']]['model'];
    $model::validateValues([
      'name'=>$_POST['user-name'],
      'password'=>$password,
      'confirmation'=>$password
    ]);
    $user = $model::find_by(['name'=>$_POST['user-name']]);
    if ($user) {
      ApplicationException::create(ApplicationException::DUPLICATED_USER_NAME);
    }
    if (ApplicationException::isStored()) {
      $con->page = 'manage-account';
      return false;
    }
    /* Succeed for registration */
    $model::create(['name'=>$_POST['user-name'], 'password'=>$password]);
    $renderer = new Renderer('');
    $mail = new Mailers();
    $mail->from = 'mail-from@example.com';
    $mail->to = $_POST['email'];
    global $system_name;
    $mail->subject = "Welcome to $system_name";
    $mail->body = $renderer->render(['template'=>'_register-email-text.php', 'account_name'=>$_POST['user-name'], 'password'=>$password, 'account_type'=>$_POST['account-type']]);
    $mail->send($con);
    return true;
  }

  static function searchProduct(MainController $con) {
    # - params
    #   - query
    #   - (min-price)
    #   - (max-price)
    #   - (show-banned)
    #   - (show-not-banned)
    #   - submit[search|index|dealing]
    # - GET method action
    if (!isset($_GET['query'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'search-product';
      return false;
    }
    $start = (isset($_GET['start'])) ? $_GET['start']: 1;
    if (isset($_GET['submit']['search']) 
        || (isset($_GET['query']) && !empty($_GET['query']))) {
      // return name matched products matched advanced search query
      if (isset($_GET['min-price']) && isset($_GET['max-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name and price between :minp and :maxp", [
                                  'name'=>"%{$_GET['query']}%",
                                  'minp'=>$_GET['min-price'],
                                  'maxp'=>$_GET['max-price']
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name and price between :minp and :maxp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%",
          'minp'=>$_GET['min-price'],
          'maxp'=>$_GET['max-price']
        ]);
      } else if (isset($_GET['min-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name and price >= :minp", [
                                  'name'=>"%{$_GET['query']}%",
                                  'minp'=>$_GET['min-price']
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name and price >= :minp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%",
          'minp'=>$_GET['min-price']
        ]);
      } else if (isset($_GET['max-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name and price <= :maxp", [
                                  'name'=>"%{$_GET['query']}%",
                                  'maxp'=>$_GET['max-price']
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name and price <= :maxp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%",
          'maxp'=>$_GET['max-price']
        ]);
      } else {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name", [
                                  'name'=>"%{$_GET['query']}%"
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%"
        ]);
      }
    } else if (isset($_GET['submit']['dealing'])) {
      // return dealed products matched advanced search query
      if (SessionController::currentLoginType() != SessionController::LOGIN_TYPE_DEALER) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        $con->page = 'search-product';
        return false;
      }
      if (isset($_GET['min-price']) && isset($_GET['max-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name and dealer_id = :dealer_id and price between :minp and :maxp", [
                                  'name'=>"%{$_GET['query']}%",
                                  'minp'=>$_GET['min-price'],
                                  'maxp'=>$_GET['max-price'],
                                  'dealer_id'=>SessionController::currentUser()->id
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name and dealer_id = :dealer_id and price between :minp and :maxp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%",
          'minp'=>$_GET['min-price'],
          'maxp'=>$_GET['max-price'],
          'dealer_id'=>SessionController::currentUser()->id
        ]);
      } else if (isset($_GET['min-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name and dealer_id = :dealer_id and price >= :minp", [
                                  'name'=>"%{$_GET['query']}%",
                                  'minp'=>$_GET['min-price'],
                                  'dealer_id'=>SessionController::currentUser()->id
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name and dealer_id = :dealer_id and price >= :minp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%",
          'minp'=>$_GET['min-price'],
          'dealer_id'=>SessionController::currentUser()->id
        ]);
      } else if (isset($_GET['max-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name and dealer_id = :dealer_id and price <= :maxp", [
                                  'name'=>"%{$_GET['query']}%",
                                  'maxp'=>$_GET['max-price'],
                                  'dealer_id'=>SessionController::currentUser()->id
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name and dealer_id = :dealer_id and price <= :maxp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%",
          'maxp'=>$_GET['max-price'],
          'dealer_id'=>SessionController::currentUser()->id
        ]);
      } else {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE name like :name and dealer_id = :dealer_id", [
                                  'name'=>"%{$_GET['query']}%",
                                  'dealer_id'=>SessionController::currentUser()->id
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE name like :name and dealer_id = :dealer_id ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'name'=>"%{$_GET['query']}%",
          'dealer_id'=>SessionController::currentUser()->id
        ]);
      }
    } else /*if (isset($_GET['submit']['index']) || $_GET['query'] == '')*/ {
      // return all products matched advanced search query
      if (isset($_GET['min-price']) && isset($_GET['max-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE price between :minp and :maxp", [
                                  'minp'=>$_GET['min-price'],
                                  'maxp'=>$_GET['max-price']
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE price between :minp and :maxp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'minp'=>$_GET['min-price'],
          'maxp'=>$_GET['max-price']
        ]);
      } else if (isset($_GET['min-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE price >= :minp", [
                                  'minp'=>$_GET['min-price']
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE price >= :minp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'minp'=>$_GET['min-price']
        ]);
      } else if (isset($_GET['max-price'])) {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable()
                                ." WHERE price <= :maxp", [
                                  'maxp'=>$_GET['max-price']
                                ])[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini WHERE price <= :maxp ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, [
          'start'=>$start,
          'end'=>$end,
          'maxp'=>$_GET['max-price']
        ]);
      } else {
        $max = ModelBase::query("SELECT count(*) as count FROM "
                                .Products::getTable())[0]['count'];
        $end = ($max > $start + 9) ? $start + 9 : $max;
        $sql= "SELECT id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id FROM (SELECT @row:=@row+1 as row, id, name, condition_type, stock, price, description, created_date, suspention, dealer_id, delivery_type_id  FROM products, (select @row:=0) ini ORDER BY id) result WHERE row between :start and :end";
        $results = Products::query($sql, ['start'=>$start, 'end'=>$end]);
      }
    }
    $con->other_params['results'] = $results;
    $con->other_params['max'] = $max;
    $con->page = 'search-product';
    return true;
    
    return false;
  }

  static function updateProduct(MainController $con) {
    # - params
    #   - product-id
    #   - product-name
    #   - product-price
    #   - product-delivery(delivery_type_id)
    #   - product-condition
    #   - units (product_stock)
    #   - product-description
    # - POST method action
    if (!isset($_POST['product-id'])
        || !isset($_POST['product-name'])
        || !isset($_POST['product-price'])
        || !isset($_POST['product-delivery'])
        || !isset($_POST['product-condition'])
        || !isset($_POST['units'])
        || !isset($_POST['product-description'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'product-register';
      return false;
    }
    if (SessionController::currentLoginType() != SessionController::LOGIN_TYPE_DEALER) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'top';
      return false;
    }
    $dealer = SessionController::currentUser();
    if ($_POST['product-id'] == 0) {
      // register
      Products::validateValues([
        'name'=>$_POST['product-name'],
        'price'=>$_POST['product-price'],
        'delivery_type_id'=>$_POST['product-delivery'],
        'condition_type'=>$_POST['product-condition'],
        'stock'=>$_POST['units'],
        'dealer_id'=>$dealer->id
      ]);
      if (ApplicationException::isStored()) {
        $con->page = 'product-register';
        return false;
      }
      Products::create([
        'name'=>$_POST['product-name'],
        'condition_type'=>$_POST['product-condition'],
        'stock'=>$_POST['units'],
        'price'=>$_POST['product-price'],
        'description'=>$_POST['product-description'],
        'dealer_id'=>$dealer->id,
        'delivery_type_id'=>$_POST['product-delivery']
      ]);
      $con->page = 'manage-product';
      return true;
    } else {
      // update
      $product = Products::find_by(['id'=>$_POST['product-id']]);
      if ($product == null) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        $con->page = 'manage-product';
        return false;
      }
      if ($dealer->id != $product->dealer_id) {
        ApplicationException::create(ApplicationException::INVALID_OPERATION);
        $con->page = 'manage-product';
        return false;
      }
      Products::validateValues([
        'name'=>$_POST['product-name'],
        'price'=>$_POST['product-price'],
        'delivery_type_id'=>$_POST['product-delivery'],
        'condition_type'=>$_POST['product-condition'],
        'stock'=>$_POST['units'],
        'dealer_id'=>$dealer->id
      ]);
      if (ApplicationException::isStored()) {
        $con->page = 'product-register';
        return false;
      }
      $product->name = $_POST['product-name'];
      $product->price = $_POST['product-price'];
      $product->delivery_type_id = $_POST['product-delivery'];
      $product->condition_type = $_POST['product-condition'];
      $product->stock = $_POST['units'];
      $product->save();
      $con->page = 'manage-product';
      return true;
    }
    return false;
  }

  static function addToCart(MainController $con) {
    # - params
    #   - product_id
    #   - units
    # - POST method action
    if (!isset($_POST['product_id']) || !isset($_POST['units'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'shopping-basket';
      return false;
    }
    if ($_POST['units'] <= 0) {
      ApplicationException::create(ApplicationException::INVALID_PURCHASE_UNITS);
      $con->page = 'shopping-basket';
      return false;
    }
    if (SessionController::currentLoginType() != SessionController::LOGIN_TYPE_MEMBER) {
      ApplicationException::create(ApplicationException::PURCHASE_BY_NON_MEMBER);
      $con->page = 'shopping-basket';
      return false;
    }
    $product = Products::find_by(['id'=>$_POST['product_id']]);
    if ($product == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'shopping-basket';
      return false;
    }
    SessionController::addToCart($_POST['product_id'], $_POST['units']);
    $con->page = 'shopping-basket';
    return true;
  }
  
  static function removeFromCart(MainController $con) {
    # - params
    #   - product_id
    # - POST method action
    if (!isset($_POST['product_id'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'shopping-basket';
      return false;
    }
    if (SessionController::currentLoginType() != SessionController::LOGIN_TYPE_MEMBER) {
      ApplicationException::create(ApplicationException::PURCHASE_BY_NON_MEMBER);
      $con->page = 'shopping-basket';
      return false;
    }
    $product = Products::find_by(['id'=>$_POST['product_id']]);
    if ($product == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'shopping-basket';
      return false;
    }
    if (!SessionController::isInCart($_POST['product_id'])) {
      ApplicationException::create(ApplicationException::REMOVE_MISSING_PURCHASE_ITEM);
      $con->page = 'shopping-basket';
      return false;
    }
    SessionController::removeFromCart($_POST['product_id']);
    $con->page = 'shopping-basket';
    return true;
  }
  
  static function purchaseProduct(MainController $con) {
    if (SessionController::currentLoginType() != SessionController::LOGIN_TYPE_MEMBER) {
      ApplicationException::create(ApplicationException::PURCHASE_BY_NON_MEMBER);
      $con->page = 'top';
      return false;
    }
    $member = SessionController::currentUser();
    $cart_items = SessionController::loadCart();
    foreach ($cart_items as $item) {
      // stockチェック
      if ($item['product']->stock < $item['units']) {
        SessionController::addToCart($item['product']->id, $item['product']->stock);
        ApplicationException::create(ApplicationException::LACK_OF_PRODUCT);
      }
    }
    if (ApplicationException::isStored()) {
      $con->page = 'shopping-basket';
      return false;
    }
    foreach ($cart_items as $item) {
      // 購入処理
      $item['product']->stock -= $item['units'];
      $item['product']->save();
      PurchasedProducts::insert([
        'member_id'=>$member->id,
        'product_id'=>$item['product']->id,
        'purchased_units'=>$item['units']
      ]);
    }
    SessionController::resetCart();
    $con->page = 'purchased';
    return true;
  }

  static function editProduct(MainController $con) {
    # - params
    #   - product_id
    #   - (units)
    /* check if product dealer == current user */
    if (!isset($_POST['product_id'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-product';
      return false;
    }
    if (SessionController::currentLoginType() != SessionController::LOGIN_TYPE_DEALER) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-product';
      return false;
    }
    $product = Products::find_by(['id'=>$_POST['product_id']]);
    if ($product == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-product';
      return false;
    }
    if ($product->dealer_id != SessionController::currentUser()->id) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-product';
      return false;
    }
    $dealer = Dealers::find_by(['id'=>$product->dealer_id]);
    if ($dealer == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'manage-product';
      return false;
    }
    if (isset($_POST['submit']['update'])) {
      // link to update product page
      header("Location: ?p=product-register&product_id=".urlencode($_POST['product_id']));
      exit;
    }
    if (isset($_POST['submit']['delete'])) {
      // delete product and back to manage product page
      $product->destroy();
      $sql= "SELECT id, name, password FROM (SELECT @row:=@row+1 as row, id, name, password FROM members, (select @row:=0) ini ORDER BY id) result WHERE row between :start and :end";
      $results = Products::query($sql, ['start'=>1, 'end'=>10]);
      $con->other_params['results'] = $results;
      $con->page = "manage-product";
      return true;
    }
    ApplicationException::create(ApplicationException::INVALID_OPERATION);
    $con->page = 'manage-product';
    return false;
  }
}
 ?>
