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

<section class="relative w-full overflow-hidden bg-white">
    <div class="relative h-[60vh] md:h-[70vh] w-full">
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
    <div class="relative z-10 max-w-5xl mx-auto px-4 -mt-32 md:-mt-48">
        <div class="bg-white/30 backdrop-blur-xl p-8 md:p-10 rounded-3xl text-center border border-primary/30">
            <span class="block text-sm md:text-base font-semibold tracking-widest text-info-600 uppercase mb-4"><?php echo esc_html($copy_en); ?></span>
            <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tighter leading-tight mb-6"><?php echo esc_html($main_copy); ?></h1>
            <div class="w-16 h-1 bg-primary mx-auto mb-8 md:mb-10"></div>
            <p class="max-w-2xl mx-auto text-base md:text-lg text-gray-800 leading-relaxed mb-10 md:mb-12"><?php echo nl2br(esc_html($sub_copy)); ?></p>
            <a href="<?php echo home_url('/contact/'); ?>" class="inline-flex items-center px-8 py-4 bg-cta text-white rounded-full font-bold text-lg hover:brightness-110 transition-colors"><?php echo esc_html($button_text); ?></a>
        </div>
    </div>
</section>