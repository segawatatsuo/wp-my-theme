<?php
/*
Template Name: Contact Thanks
*/

get_header();

// サンクス設定ページ（thanks）から情報を取得
$template_page = get_page_by_path('thanks', OBJECT, 'page');
$template_id = $template_page ? $template_page->ID : null;

// ACFから値を取得（空の場合はデフォルト値を表示）
$thanks_title = get_field('thanks_title', $template_id) ?: 'お問い合わせ送信完了';
$thanks_message = get_field('thanks_message', $template_id) ?: 'お問い合わせいただき、誠にありがとうございます。
送信内容は正常に受け付けられました。';
$thanks_notice = get_field('thanks_notice', $template_id) ?: 'ご入力いただいたメールアドレス宛に、自動返信メールをお送りいたしました。
内容を確認の上、担当者より通常1〜2営業日以内にご連絡させていただきます。';
$thanks_warning = get_field('thanks_warning', $template_id) ?: '※数時間経っても自動返信メールが届かない場合は、迷惑メールフォルダをご確認いただくか、
お手数ですがお電話にてお問い合わせください。';

?>

<section class="py-24 bg-white min-h-[60vh] flex items-center">
    <div class="max-w-4xl mx-auto px-6 text-center">

        <div class="mb-10 flex justify-center">
            <div class="w-20 h-20 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h2 class="font-serif text-3xl font-bold text-gray-900 mb-6">
            <!-- お問い合わせ送信完了 -->
            <?php echo esc_html($thanks_title); ?>
        </h2>

        <div class="space-y-4 mb-12">
            <p class="text-gray-600 leading-loose">
                <!-- お問い合わせいただき、誠にありがとうございます。<br>
                送信内容は正常に受け付けられました。 -->
                <?php echo nl2br(esc_html($thanks_message)); ?>
            </p>
            <p class="text-gray-500 text-sm leading-loose">
                <!-- ご入力いただいたメールアドレス宛に、自動返信メールをお送りいたしました。<br>
                内容を確認の上、担当者より通常2〜3営業日以内にご連絡させていただきます。 -->
                <?php echo nl2br(esc_html($thanks_notice)); ?>
            </p>
        </div>

        <div class="">
            <p class="text-gray-400 text-xs mb-8">
                <!-- ※数時間経っても自動返信メールが届かない場合は、迷惑メールフォルダをご確認いただくか、<br class="hidden md:block">
                お手数ですがお電話にてお問い合わせください。 -->
                <?php echo nl2br(esc_html($thanks_warning)); ?>
            </p>

            <a href="<?= esc_url(home_url('/')) ?>"
                class="inline-flex items-center justify-center px-10 py-4 border border-gray-200 texthanks_warningt-gray-700 font-bold rounded-md hover:bg-gray-50 transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                トップページに戻る
            </a>
        </div>

    </div>
</section>

<script>
    // ブラウザの履歴に現在のURLを上書きし、戻る操作を現在のページに固定する
    (function() {
        window.history.pushState(null, null, location.href);
        window.onpopstate = function() {
            // 戻るボタンが押されたら、トップページまたは入力画面に強制リダイレクト
            location.href = '<?php echo home_url("/"); ?>';
        };
    })();
</script>

<?php get_footer(); ?>