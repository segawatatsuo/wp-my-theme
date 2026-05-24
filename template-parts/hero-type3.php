<?php
// 各パーツ内で必要なIDを再定義
$pages = [
    'visual' => get_page_by_path('top-mainvisual'),
    'settings' => get_page_by_path('settings'),
];

$target_id   = $pages['visual'] ? $pages['visual']->ID : null;
$hero_img    = get_field('hero_img', $target_id); // これが画像データ
$copy_en     = get_field('copy_in_english', $target_id);
$main_copy   = get_field('main_copy', $target_id);
$sub_copy    = get_field('copy', $target_id);
$button_text = get_field('button_text', $target_id);

// 電話番号取得
$phone = $pages['settings'] ? get_field('phone_number', $pages['settings']->ID) : '';
$phone_clean = str_replace('-', '', $phone);
?>


<section class="relative overflow-hidden bg-white min-h-[420px] flex items-center">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 right-0 h-full bg-orange-bg rounded-bl-[80px]"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-12 w-full">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-6">
            <div class="flex-1">
                <span class="block text-sm md:text-base font-semibold tracking-widest text-info-600 uppercase mb-4"><?php echo esc_html($copy_en); ?></span>
                <div class="max-w-xl">
                    <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tighter leading-tight mb-6"><?php echo esc_html($main_copy); ?></h1>
                    <p class="text-lg text-gray-600 mb-8"><?php echo nl2br(esc_html($sub_copy)); ?></p>
                </div>
                <a href="<?php echo home_url('/contact/'); ?>" class="inline-flex items-center px-8 py-4 bg-cta text-white rounded-full font-bold text-lg hover:brightness-110 transition-colors">無料相談はこちら</a>
            </div>
            <div class="relative flex-shrink-0 lg:transform lg:-translate-x-20 xl:-translate-x-32 z-20">
                <div class="absolute inset-0 bg-orange-200 opacity-50 transform translate-x-6 translate-y-6 -z-10" style="border-radius: 70% 30% 50% 50% / 55% 48% 30% 30%;"></div>
                <div class="w-72 h-72 lg:w-96 lg:h-96 bg-gray-100 overflow-hidden border-4 border-white relative shadow-xl" style="border-radius: 70% 30% 50% 50% / 55% 48% 30% 30%;">
                    <?php if ($hero_img): ?>

                        <?php
                        $image_id = is_array($hero_img)
                            ? $hero_img['ID']
                            : $hero_img;
                        ?>

                        <?= wp_get_attachment_image(
                            $image_id,
                            'full',
                            false,
                            [
                                'class' => 'absolute inset-0 w-full h-full object-cover',
                                'loading' => 'eager',
                                'fetchpriority' => 'high',
                                'decoding' => 'async',
                            ]
                        ); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>