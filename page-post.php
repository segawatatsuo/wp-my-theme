<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        <input type="text" name="user_name" placeholder="お名前" required>
        <input type="email" name="user_email" placeholder="メールアドレス" required>
        <textarea name="user_message" placeholder="お問い合わせ内容" required></textarea>

        <?php wp_nonce_field('my_contact_form', 'my_contact_nonce'); ?>

        <button type="submit" name="submit_contact">送信する</button>
    </form>
</body>

</html>