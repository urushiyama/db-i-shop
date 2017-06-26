<?php
require_once '_C_SessionController.php';
require_once '_C_Renderer.php';
require_once '_C_ApplicationException.php';
require_once '_C_Members.php';
require_once '_C_Dealers.php';
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
    
    return false;
  }

  static function updateProduct(MainController $con) {
    return false;
  }

  static function addToCart(MainController $con) {
    return false;
  }

  static function editProduct(MainController $con) {
    /* check if product dealer == current user */
    if (isset($_POST['submit']['update'])) {
      // link to update product page
      header("Location: ?p=product-register&product_id=".urlencode($_POST['product_id']));
      exit;
    }
    if (isset($_POST['submit']['delete'])) {
      // delete product and back to manage product page
      $con->page = "?manage-product";
      return false;
    }
    return false;
  }
}
 ?>
