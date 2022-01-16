<?php
  session_start();
	require('./library.php');
  $mode = 'input';
  $errormessage = [];

  $conn = dbconnect();
  $forms = $conn->query('select message, created from forms order by id asc');
  if (!$forms) {
    die($conn->error);
  }

  if( isset($_POST['back']) && $_POST['back'] ){
      // 何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm'] ){
      // 確認画面
    if( !$_POST['name'] ) {
        $errormessage[] = "名前を入力してください";
    } else if( mb_strlen($_POST['name']) > 100 ){
        $errormessage[] = "名前は100文字以内にしてください";
    }
      $_SESSION['name'] = h($_POST['name']);

      if( !$_POST['email'] ) {
          $errormessage[] = "Eメールを入力してください";
      } else if( mb_strlen($_POST['email']) > 200 ){
          $errormessage[] = "Eメールは200文字以内にしてください";
    } else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
        $errormessage[] = "メールアドレスが不正です";
      }
      $_SESSION['email'] = h($_POST['email']);

      if( !$_POST['message'] ){
          $errormessage[] = "お問い合わせ内容を入力してください";
      } else if( mb_strlen($_POST['message']) > 500 ){
          $errormessage[] = "お問い合わせ内容は500文字以内にしてください";
      }
      $_SESSION['message'] = h($_POST['message']);

      if( $errormessage ){
        $mode = 'input';
    } else {
        $mode = 'confirm';
    }
  } else if( isset($_POST['send']) && $_POST['send'] ){
    // 送信ボタンを押したとき
    $conn = dbconnect();
    $stmt = $conn->prepare('insert into forms (name, email, message) VALUES (?, ?, ?)');
	if (!$stmt) {
		die($conn->error);
	}
	$stmt->bind_param('sss', $_SESSION['name'], $_SESSION['email'], $_SESSION['message']);
	$success = $stmt->execute();
	if (!$success) {
		die($conn->error);
	}

    $_SESSION = array();
    $mode = 'send';
  } else {
    $_SESSION['name'] = "";
    $_SESSION['email'] = "";
    $_SESSION['message'] = "";
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>お問い合わせフォーム</title>
</head>
<body>
  <?php if( $mode == 'input' ){ ?>
    <!-- 入力画面 -->
    <?php
      if( $errormessage ){
        echo '<div style="color:red;">';
        echo implode('<br>', $errormessage );
        echo '</div>';
      }
    ?>
    <form action="./index.php" method="post">
      名前    <input type="text"    name="name" value="<?php echo $_SESSION['name'] ?>"><br>
      Eメール <input type="email"   name="email"    value="<?php echo $_SESSION['email'] ?>"><br>
      お問い合わせ内容<br>
      <textarea cols="40" rows="8" name="message"><?php echo $_SESSION['message'] ?></textarea><br>
      <input type="submit" name="confirm" value="確認画面へ" />
    </form>

    <hr>

    <p>お問い合わせ一覧</p>
    <?php while ($form = $forms->fetch_assoc()) {
      $form_message = implode('<br>', $form );
      echo '<p>';
      echo $form_message;
      echo '</p>';
    }
    ?>
  <?php } else if( $mode == 'confirm' ){ ?>
    <!-- 確認画面 -->
    <form action="./index.php" method="post">
      名前    <?php echo $_SESSION['name'] ?><br>
      Eメール <?php echo $_SESSION['email'] ?><br>
      お問い合わせ内容<br>
      <?php echo nl2br($_SESSION['message']) ?><br>
      <input type="submit" name="back" value="修正する" />
      <input type="submit" name="send" value="この内容で送信する" />
    </form>
  <?php } else { ?>
    <!-- 完了画面 -->
    <p>お問い合わせありがとうございました。</p>
  <?php } ?>
</body>
</html>
