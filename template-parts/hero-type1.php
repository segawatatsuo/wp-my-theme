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

<section class="relative w-full h-[80vh] min-h-[500px] flex items-center justify-center bg-white overflow-hidden">

    <!-- HERO画像 -->

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


    <!--
    <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-black/20 to-transparent"></div>
    -->
    <div class="absolute inset-0 bg-gradient-to-br from-[rgb(0_0_0/0.4)] via-[rgb(0_0_0/0.2)] to-transparent"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 text-center text-white">
        <span class="block text-sm md:text-base font-semibold tracking-widest text-accent uppercase mb-3"><?php echo esc_html($copy_en); ?></span>
        <h1 class="font-serif text-white text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tighter leading-tight mb-6 [&_br]:hidden md:[&_br]:inline"><?php echo nl2br(esc_html($main_copy)); ?></h1>
        <p class="font-serif max-w-3xl mx-auto text-lg md:text-xl text-white/90 leading-relaxed mb-10 [&_br]:hidden md:[&_br]:inline"><?php echo nl2br(esc_html($sub_copy)); ?></p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?php echo home_url('/contact/'); ?>" class="inline-flex items-center px-8 py-4 bg-cta text-white rounded-full font-bold text-lg hover:brightness-110 transition-all duration-300 hover:scale-105"><?php echo esc_html($button_text); ?></a>




            <?php if ($phone): ?>
                <a href="tel:<?php echo $phone_clean; ?>"
                    class="relative overflow-hidden bg-primary hover:brightness-110 text-white px-6 py-5 rounded-full font-black text-lg transition-all duration-300 hover:scale-105 flex items-center justify-center gap-3 shadow-lg group">
                    <!-- 背景に敷く白の半透明レイヤー（backdrop-blurで後ろの写真と青をボカす） -->
                    <span class="absolute inset-0 bg-white/20 backdrop-blur-md transition-colors group-hover:bg-white/30"></span>
                    <!-- 文字やアイコンが背景に埋もれないように z-10 で前面に出す -->
                    <svg class="w-6 h-6 relative z-10" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    <span class="tracking-wider relative z-10"><?php echo esc_html($phone); ?></span>
                </a>
            <?php endif; ?>


        </div>
    </div>
</section>