<?php

/**
 * Template Name: サイト基本設定表示用
 * Description: スラッグ 'common_settings' の固定ページで使用するテンプレート
 */

get_header(); ?>

<main class="container mx-auto py-16 px-4 max-w-4xl">

    <div class="text-center mb-16 border-b border-gray-200 pb-10">
        <h1 class="text-4xl font-bold font-shippori-mincho text-gray-900 mb-2">
            <?php echo esc_html(get_field('office_name')); ?>
        </h1>
        <?php if ($eng_name = get_field('office_name_in_english')): ?>
            <p class="text-sm tracking-widest text-gray-500 uppercase">
                <?php echo esc_html($eng_name); ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
        <dl class="divide-y divide-gray-100">


            <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-50 transition-colors">
                <dt class="text-sm font-semibold text-gray-600">郵便番号</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-shippori-mincho">
                    <?php echo nl2br(esc_html(get_field('postal_code'))); ?>
                </dd>
            </div>


            <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-50 transition-colors">
                <dt class="text-sm font-semibold text-gray-600">住所</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-shippori-mincho">
                    <?php echo nl2br(esc_html(get_field('office_address'))); ?>
                </dd>
            </div>

            <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-50 transition-colors">
                <dt class="text-sm font-semibold text-gray-600">電話番号 / FAX</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <div class="font-bold text-lg">TEL: <?php echo esc_html(get_field('phone_number')); ?></div>
                    <div class="text-gray-500">FAX: <?php echo esc_html(get_field('fax_number')); ?></div>
                </dd>
            </div>

            <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-50 transition-colors">
                <dt class="text-sm font-semibold text-gray-600">電話受付時間</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?php echo esc_html(get_field('reception_hours')); ?>
                </dd>
            </div>

            <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-50 transition-colors">
                <dt class="text-sm font-semibold text-gray-600">メールアドレス</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a href="mailto:
                    <?php echo antispambot(get_field('mail_address')); ?>" class="text-blue-600 hover:underline">
                        <?php echo esc_html(get_field('mail_address'));
                        ?>
                    </a>
                </dd>
            </div>

        </dl>
    </div>

    <div class="mt-12 text-center text-xs text-gray-400">
        <p>copy rights：</p>
        <p class="mt-2 text-sm italic">
            © <?php echo date('Y'); ?> <?php echo esc_html(get_field('copy_rights')); ?>
        </p>
    </div>

</main>

<?php get_footer(); ?>