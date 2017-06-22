<?php
class Crypt {

  /* Crypt helper
  ** Referenced: https://blog.ohgaki.net/encrypt-decrypt-using-openssl
  */

  const APPLICATION_PASSWORD = 'databaseI';

 /**
  * decrypt AES 256
  *
  * @param data $edata
  * @param string $password
  * @return decrypted data
  */
  static function decrypt($edata, $password) {
    $data = base64_decode($edata);
    $salt = substr($data, 0, 16);
    $ct = substr($data, 16);

    $rounds = 3; // depends on key length
    $data00 = $password.$salt;
    $hash = array();
    $hash[0] = hash('sha256', $data00, true);
    $result = $hash[0];
    for ($i = 1; $i < $rounds; $i++) {
      $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
      $result .= $hash[$i];
    }
    $key = substr($result, 0, 32);
    $iv  = substr($result, 32,16);

    return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
  }

  /**
   * crypt AES 256
   *
   * @param data $data
   * @param string $password
   * @return base64 encrypted data
   */
  static function encrypt($data, $password) {
    // Set a random salt
    $salt = openssl_random_pseudo_bytes(16);

    $salted = '';
    $dx = '';
    // Salt the key(32) and iv(16) = 48
    while (strlen($salted) < 48) {
      $dx = hash('sha256', $dx.$password.$salt, true);
      $salted .= $dx;
    }

    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);

    $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
    return base64_encode($salt . $encrypted_data);
  }

  static function randomHexString($n = 8) {
    return bin2hex(openssl_random_pseudo_bytes($n/2));
  }
}

if (realpath($argv[0]) == __FILE__) {
  $plain = "Hoge Hoge Foo Bar";
  $cipher = Crypt::encrypt($plain, "password");
  $replain = Crypt::decrypt($cipher, "password");
  var_dump($plain);
  var_dump($replain);
}
?>
