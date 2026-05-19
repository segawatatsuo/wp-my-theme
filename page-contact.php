<?php
/*
Template Name: Contact
*/

get_header();

$step = $_POST['step'] ?? 'input';

// CSRF対策
if (!empty($_POST)) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'contact_form')) {
        wp_die('不正なリクエストです');
    }
}

// データの受け取り
$namae   = sanitize_text_field($_POST['namae'] ?? '');
$tel     = sanitize_text_field($_POST['tel'] ?? '');
$email   = sanitize_email($_POST['email'] ?? '');
$message = sanitize_textarea_field($_POST['message'] ?? '');

$errors = [];

// バリデーション
if ($step === 'confirm' || $step === 'send') {
    if ($namae === '') $errors['namae'] = 'お名前を入力してください';
    if ($email === '' || !is_email($email)) $errors['email'] = '正しいメールアドレスを入力してください';
    if ($message === '') $errors['message'] = 'お問い合わせ内容を入力してください';

    if (!empty($errors)) {
        $step = 'input';
    }
}



// 送信処理
if ($step === 'send') {
    // --- メール設定ページの取得 ---
    $template_page = get_page_by_path('mail-settings', OBJECT, 'page');
    $template_id   = $template_page ? $template_page->ID : null;

    // ACFから値を取得
    $admin_subject  = get_field('mail_admin_subject', $template_id);
    $admin_body_raw = get_field('mail_admin_body', $template_id);
    $user_subject   = get_field('mail_user_subject', $template_id);
    $user_body_raw  = get_field('mail_user_body', $template_id);

    // デフォルト文面（ACFが空の場合の保険）
    $admin_subject  = $admin_subject ?: 'サイトからのお問い合わせ';
    $admin_body_raw = $admin_body_raw ?: "名前: {name}\n電話: {tel}\nメール: {email}\n内容: {message}";
    $user_subject   = $user_subject ?: 'お問い合わせありがとうございます';
    $user_body_raw  = $user_body_raw ?: "{name} 様\n\nお問い合わせありがとうございます。";

    // 置換用データ
    $placeholders = [
        '{name}'    => $namae,
        '{tel}'     => $tel,
        '{email}'   => $email,
        '{message}' => $message,
        '{site_name}' => get_bloginfo('name'),
    ];

    $admin_body = str_replace(array_keys($placeholders), array_values($placeholders), $admin_body_raw);
    $user_body  = str_replace(array_keys($placeholders), array_values($placeholders), $user_body_raw);

    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    // 管理者とユーザーへ送信
    wp_mail(get_option('admin_email'), $admin_subject, $admin_body, $headers);
    wp_mail($email, $user_subject, $user_body, $headers);

    wp_redirect(home_url('/thanks')); // 送信完了ページへ
    exit;
}


?>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6">

        <?php if ($step === 'input'): ?>
            <div class="text-center mb-20">
                <h2 class="font-serif text-2xl font-bold text-gray-900 mb-6 flex items-center justify-center gap-3">
                    お電話でのお問い合わせはこちら
                </h2>

                <?php
                $page = get_page_by_path('settings');
                if ($page) {
                    $phone = get_field('phone_number', $page->ID);
                    $phone_clean = str_replace('-', '', $phone);
                    $reception_hours = get_field('reception_hours', $page->ID);
                }
                ?>

                <div class="flex flex-col items-center">
                    <a href="tel:<?php echo $phone_clean; ?>"
                        class="flex items-center gap-3 text-4xl md:text-5xl font-black text-primary hover:opacity-80 transition-opacity tracking-tighter">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                        </svg>
                        <span class="tracking-wider"><?php echo $phone; ?></span>
                    </a>
                    <p class="mt-4 text-gray-600 font-medium text-sm">
                        <?php echo $reception_hours; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center mb-12">
            <h2 class="font-serif text-2xl font-bold text-gray-900 mb-4">
                <?= $step === 'confirm' ? '入力内容の確認' : 'メールフォームでのお問い合わせ' ?>
            </h2>
            <p class="text-gray-500 text-sm tracking-wider">
                <?= $step === 'confirm' ? '内容に間違いがなければ送信してください' : '必須項目は必ず入力してください' ?>
            </p>
        </div>

        <form method="POST" action="<?= esc_url(get_permalink()) ?>" class="max-w-5xl mx-auto mb-24">
            <?php wp_nonce_field('contact_form'); ?>

            <div class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-8 items-center border-b border-gray-100 pb-8">
                    <label class="font-bold text-gray-700 flex items-center gap-2">
                        お名前 <span class="text-[10px] bg-red-600 text-white px-2 py-0.5 rounded leading-none">必須</span>
                    </label>
                    <div class="md:col-span-3">
                        <?php if ($step === 'input'): ?>
                            <input type="text" name="namae" value="<?= esc_attr($namae) ?>" placeholder="例：山田 太郎"
                                class="w-full px-4 py-4 bg-gray-50 border <?= isset($errors['namae']) ? 'border-red-500' : 'border-gray-200' ?> rounded-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-gray-300">
                            <?php if (isset($errors['namae'])): ?><p class="text-red-500 text-xs mt-2"><?= $errors['namae'] ?></p><?php endif; ?>
                        <?php else: ?>
                            <p class="text-gray-900 py-4 font-medium"><?= esc_html($namae) ?></p>
                            <input type="hidden" name="namae" value="<?= esc_attr($namae) ?>">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-8 items-center border-b border-gray-100 pb-8">
                    <label class="font-bold text-gray-700 flex items-center gap-2">
                        電話番号 <span class="text-[10px] bg-gray-400 text-white px-2 py-0.5 rounded leading-none">任意</span>
                    </label>
                    <div class="md:col-span-3">
                        <?php if ($step === 'input'): ?>
                            <input type="tel" name="tel" value="<?= esc_attr($tel) ?>" placeholder="例：090-0000-0000"
                                class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-gray-300">
                        <?php else: ?>
                            <p class="text-gray-900 py-4 font-medium"><?= esc_html($tel ?: '未入力') ?></p>
                            <input type="hidden" name="tel" value="<?= esc_attr($tel) ?>">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-8 items-center border-b border-gray-100 pb-8">
                    <label class="font-bold text-gray-700 flex items-center gap-2">
                        メールアドレス <span class="text-[10px] bg-red-600 text-white px-2 py-0.5 rounded leading-none">必須</span>
                    </label>
                    <div class="md:col-span-3">
                        <?php if ($step === 'input'): ?>
                            <input type="email" name="email" value="<?= esc_attr($email) ?>" placeholder="例：sample@example.com"
                                class="w-full px-4 py-4 bg-gray-50 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-200' ?> rounded-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-gray-300">
                            <?php if (isset($errors['email'])): ?><p class="text-red-500 text-xs mt-2"><?= $errors['email'] ?></p><?php endif; ?>
                        <?php else: ?>
                            <p class="text-gray-900 py-4 font-medium"><?= esc_html($email) ?></p>
                            <input type="hidden" name="email" value="<?= esc_attr($email) ?>">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-8 border-b border-gray-100 pb-8">
                    <label class="font-bold text-gray-700 flex items-center gap-2 pt-4">
                        お問い合わせ内容 <span class="text-[10px] bg-red-600 text-white px-2 py-0.5 rounded leading-none">必須</span>
                    </label>
                    <div class="md:col-span-3">
                        <?php if ($step === 'input'): ?>
                            <textarea name="message" rows="8" placeholder="ご相談内容をご記入ください"
                                class="w-full px-4 py-4 bg-gray-50 border <?= isset($errors['message']) ? 'border-red-500' : 'border-gray-200' ?> rounded-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all resize-none placeholder:text-gray-300"><?= esc_textarea($message) ?></textarea>
                            <?php if (isset($errors['message'])): ?><p class="text-red-500 text-xs mt-2"><?= $errors['message'] ?></p><?php endif; ?>
                        <?php else: ?>
                            <p class="text-gray-900 py-4 font-medium leading-loose"><?= nl2br(esc_html($message)) ?></p>
                            <input type="hidden" name="message" value="<?= esc_attr($message) ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-16 flex flex-col md:flex-row justify-center items-center gap-4 px-6">
                <?php if ($step === 'input'): ?>
                    <input type="hidden" name="step" value="confirm">
                    <button type="submit"
                        class="inline-flex items-center justify-center w-full md:w-auto min-w-[320px] px-12 py-5 bg-primary text-white font-bold rounded-md shadow-lg shadow-primary/10 hover:bg-primary/90 hover:-translate-y-0.5 transition-all cursor-pointer group">
                        入力内容を確認する
                        <svg class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                <?php else: ?>
                    <button type="submit" name="step" value="input"
                        class="inline-flex items-center justify-center w-full md:w-auto min-w-[200px] px-8 py-4 bg-gray-200 text-gray-700 font-bold rounded-md hover:bg-gray-300 transition-all cursor-pointer">
                        戻って修正する
                    </button>
                    <button type="submit" name="step" value="send"
                        class="inline-flex items-center justify-center w-full md:w-auto min-w-[320px] px-12 py-5 bg-primary text-white font-bold rounded-md shadow-lg shadow-primary/10 hover:bg-primary/90 hover:-translate-y-0.5 transition-all cursor-pointer group">
                        この内容で送信する
                        <svg class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($step === 'input'): ?>
            <div class="max-w-5xl mx-auto border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-600 tracking-wider">プライバシーポリシー（個人情報の取り扱いについて）</h3>
                </div>
                <div class="p-8 max-h-60 overflow-y-auto text-sm text-gray-500 leading-loose space-y-4 entry-content">
                    <?php
                    // 'privacy-policy' は固定ページのスラッグに合わせて変更してください
                    $page_obj = get_page_by_path('privacy-policy');
                    if ($page_obj) :
                        // コンテンツを整形（自動改行などを適用）して出力
                        echo apply_filters('the_content', $page_obj->post_content);
                    else :
                        echo '<p>プライバシーポリシーが設定されていません。</p>';
                    endif;
                    ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>