<?php get_header(); ?>

<?php

$pages = [
    'visual'   => get_page_by_path('top-mainvisual'),
    'problem'  => get_page_by_path('top-problem'),
    'services' => get_page_by_path('top-services'),
    'reason'   => get_page_by_path('top-reason'),
    'flow'     => get_page_by_path('top-flow'),
    'greeting' => get_page_by_path('top-greeting'),
    'settings' => get_page_by_path('settings'),
];

$visual_id = $pages['visual'] ? $pages['visual']->ID : null;
$hero_img  = get_field('hero_image', $visual_id);
?>

<!-- hero -->
<section class="relative w-full h-[80vh] min-h-[500px] flex items-center justify-center bg-gray-900 overflow-hidden">

    <!-- HERO画像 -->
    <img src="<?php echo esc_url($hero_img['url']); ?>" alt="Hero Background" class="absolute inset-0 w-full h-full object-cover">

    <!-- オーバーレイ -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/30 to-transparent"></div>
    <!-- コンテンツ -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 text-center text-white">

        <!-- 英語タイトル -->
        <span class="block text-sm md:text-base font-semibold tracking-widest text-accent uppercase mb-3">
            <?php echo get_field('copy_in_english', $visual_id); ?>
        </span>

        <!-- メインタイトル -->
        <!-- [&_br]:hidden md:[&_br]:inline PCでは改行を生かしスマホでは改行させない-->
        <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tighter leading-tight mb-6 [&_br]:hidden md:[&_br]:inline">
            <?php echo nl2br(get_field('main_copy', $visual_id)); ?>
        </h1>

        <!-- 説明文（固定でもOK・後でカスタムフィールド化推奨） -->
        <!-- [&_br]:hidden md:[&_br]:inline PCでは改行を生かしスマホでは改行させない-->
        <p class="font-serif max-w-3xl mx-auto text-lg md:text-xl text-white/90 leading-relaxed mb-10 [&_br]:hidden md:[&_br]:inline">
            <?php echo nl2br(get_field('copy', $visual_id)); ?>
        </p>

        <!-- ボタン -->

        <a href="<?php echo home_url('/contact/'); ?>"
            class="inline-flex items-center px-8 py-4 bg-cta text-white rounded-full font-bold text-lg hover:brightness-110 transition-all duration-300 hover:scale-105">
            <?php echo get_field('button_text', $visual_id); ?>
        </a>


    </div>
</section>
<!-- end of hero -->

<!--hero 似顔絵-->
<!--
<section class="relative overflow-hidden bg-white min-h-[420px] flex items-center">

    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 right-0 h-full bg-orange-bg rounded-bl-[80px] rounded-tr-[80px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-12 w-full">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-6">

            <div class="flex-1">
                <span class="block text-sm md:text-base font-semibold tracking-widest text-info-600 uppercase mb-4">
                    <?php echo get_field('copy_in_english', $page->ID); ?>
                </span>

                <div class="max-w-xl">
                    <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tighter leading-tight mb-6">
                        <?php echo get_field('main_copy', $page->ID); ?>
                    </h1>

                    <p class="text-lg text-gray-600 mb-8">
                        <?php echo get_field('copy', $page->ID); ?>
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="<?php echo home_url('/contact/'); ?>"
                        class="inline-flex items-center px-8 py-4 bg-cta text-white rounded-full font-bold text-lg hover:brightness-110 transition-colors">
                        無料相談はこちら
                    </a>

                    <a href="<?php echo home_url('/contact/'); ?>"
                        class="inline-flex items-center px-8 py-4 bg-cta text-white rounded-full font-bold text-lg hover:brightness-110 transition-colors">
                        無料相談はこちら
                    </a>



                </div>
            </div>

            <div class="relative flex-shrink-0 lg:transform lg:-translate-x-20 xl:-translate-x-32 z-20">

                <div class="absolute inset-0 bg-orange-200 opacity-50 transform translate-x-6 translate-y-6 -z-10"
                    style="border-radius: 70% 30% 50% 50% / 55% 48% 30% 30%;">
                </div>

                <div class="w-72 h-72 lg:w-96 lg:h-96 bg-gray-100 overflow-hidden border-4 border-white relative shadow-xl"
                    style="border-radius: 70% 30% 50% 50% / 55% 48% 30% 30%;">

                    <img src="<?php echo esc_url($image['url']); ?>" alt="代表 サンプル太郎" class="w-full h-full object-cover object-top" />

                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-center py-3 px-2">
                        <p class="text-xs text-gray-300">サンプル行政書士事務所</p>
                        <p class="text-sm font-bold">代表　サンプル太郎</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
-->






<!--hero-->
<!-- 
<section class="relative w-full overflow-hidden" style="background-color: color-mix(in srgb, var(--color-light) 40%, white);">
    <div class="relative h-[60vh] md:h-[70vh] w-full">
        <img src="<?php echo esc_url($image['url']); ?>"
            alt=" Hero Background"
            class="absolute inset-0 w-full h-full object-cover">
    </div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 -mt-32 md:-mt-48">
        <div class="bg-white/30 backdrop-blur-xl p-8 md:p-10 rounded-3xl text-center border border-primary/30">

            <span class="block text-sm md:text-base font-semibold tracking-widest text-info-600 uppercase mb-4">
                <?php
                $en = get_field('copy_in_english', $page->ID);
                echo $en;
                ?>
            </span>
            <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tighter leading-tight mb-6">
                <?php
                $main = get_field('main_copy', $page->ID);
                echo $main;
                ?>
            </h1>
            <div class="w-16 h-1 bg-primary mx-auto mb-8 md:mb-10"></div>

            <p class="max-w-2xl mx-auto text-base md:text-lg text-gray-800 leading-relaxed mb-10 md:mb-12">
                <?php
                $copy = get_field('copy', $page->ID);
                echo $copy;
                ?>
            </p>
              <a href="<?php echo home_url('/contact/'); ?>"
                class="inline-flex items-center px-8 py-4 bg-cta text-white rounded-full font-bold text-lg hover:brightness-110 transition-colors">
                <?php
                $btn = get_field('button_text', $page->ID);
                echo $btn;
                ?>
            </a>
        </div>
    </div>
</section> 
-->
<!--hero-->





<!-- top-problem -->
<?php
$problem_id = $pages['problem'] ? $pages['problem']->ID : null;
$data = get_post_meta($problem_id, '_problem_section', true);
$data = $data ? json_decode($data, true) : [];

$head_copy    = $data['head_copy']    ?? '';
$head_copy2   = $data['head_copy2']   ?? '';
$worries      = $data['worries']      ?? [];
$omakase_head = $data['omakase_head'] ?? '';
$omakase_body = $data['omakase_body'] ?? '';
?>


<section class="py-16 md:py-24 bg-light">

    <div class="max-w-7xl mx-auto px-6">

        <!-- ヘッドコピー -->
        <div class="text-center mb-12 md:mb-16">
            <?php if ($head_copy) : ?>
                <h2 class="font-serif text-3xl md:text-5xl font-bold tracking-tight text-text-dark">
                    <?php echo esc_html($head_copy); ?>
                </h2>
            <?php endif; ?>
            <?php if ($head_copy2) : ?>
                <h3 class="font-serif text-2xl md:text-3xl font-bold mt-4 text-primary">
                    <?php echo esc_html($head_copy2); ?>
                </h3>
            <?php endif; ?>
        </div>

        <!-- お悩み実例ループ -->

        <?php if (!empty($worries)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 md:mb-20">
                <?php foreach ($worries as $index => $worry) : ?>
                    <div class="problem-card rounded-[2.5rem] p-8 bg-white border border-gray-200 shadow-sm">
                        <dl>
                            <dt class="flex items-center gap-3 mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center bg-primary">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="w-px h-6 rounded-full bg-primary/30"></div>
                                </div>


                                <span class="text-xl font-bold marker text-text-dark">
                                    <?php echo esc_html($worry['worry_title']); ?>
                                </span>

                            </dt>
                            <dd class="pl-11 font-medium leading-relaxed text-text-dark/80">
                                <?php echo nl2br(esc_html($worry['worry_content'])); ?>
                            </dd>
                        </dl>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>





        <!-- arrow -->
        <div class="flex flex-col items-center gap-0.5 mb-12 animate-bounce">
            <div class="w-0 h-0
                border-l-[20px] border-l-transparent
                border-r-[20px] border-r-transparent
                border-t-[24px] border-t-primary/40">
            </div>
            <div class="w-0 h-0
                border-l-[20px] border-l-transparent
                border-r-[20px] border-r-transparent
                border-t-[24px] border-t-primary">
            </div>
        </div>




        <!-- おまかせください -->
        <div class="max-w-4xl mx-auto rounded-[3rem] p-10 md:p-20 relative overflow-hidden bg-white border-2 border-primary">

            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-primary"></div>
            <?php if ($omakase_head) : ?>
                <h3 class="font-serif text-2xl md:text-3xl font-bold mb-8 leading-tight text-primary">
                    <?php echo esc_html($omakase_head); ?>
                </h3>
            <?php endif; ?>
            <?php if ($omakase_body) : ?>
                <p class="text-lg md:text-lg leading-loose text-text-dark">
                    <?php
                    $body = str_replace(["\r\n", "\r"], "\n", $omakase_body);
                    echo nl2br(esc_html($body));
                    ?>
                </p>
            <?php endif; ?>
        </div>


    </div>
</section>
<!-- top-problem -->




<!-- service -->



<?php
$service_id = $pages['services'] ? $pages['services']->ID : null;
$data = get_post_meta($service_id, '_services_section', true);
$data = $data ? json_decode($data, true) : [];
$section_title = $data['section_title'] ?? '';
$section_subtitle = $data['section_subtitle'] ?? '';
$services      = $data['services']      ?? [];
?>


<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if ($section_title) : ?>
            <div class="text-center mb-12 md:mb-16">
                <h2 class="font-serif text-3xl md:text-5xl font-bold tracking-tight inline-block border-b-4 border-primary pb-2 text-text-dark">
                    <?php echo esc_html($section_title); ?>
                </h2>

                <p class="font-serif text-2xl md:text-3xl font-bold mt-4 md:mt-6 text-primary">
                    <?php echo esc_html($section_subtitle); ?>
                </p>

            </div>
        <?php endif; ?>

        <?php if (!empty($services)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($services as $service) : ?>

                    <div class="group p-8 rounded-2xl bg-light hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-primary/20">

                        <?php if (!empty($service['icon'])) : ?>
                            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                <?php
                                $allowed_svg = [
                                    'svg' => [
                                        'xmlns'        => true,
                                        'fill'         => true,
                                        'viewbox'      => true,
                                        'viewBox'      => true,
                                        'width'        => true,
                                        'height'       => true,
                                        'class'        => true,
                                        'stroke'       => true,
                                        'stroke-width' => true,
                                        'aria-hidden'  => true,
                                    ],
                                    'path' => [
                                        'd'               => true,
                                        'fill'            => true,
                                        'stroke'          => true,
                                        'stroke-linecap'  => true,
                                        'stroke-linejoin' => true,
                                        'stroke-width'    => true,
                                        'fill-rule'       => true,
                                        'clip-rule'       => true,
                                    ],
                                    'circle' => [
                                        'cx'     => true,
                                        'cy'     => true,
                                        'r'      => true,
                                        'fill'   => true,
                                        'stroke' => true,
                                    ],
                                    'rect' => [
                                        'x'      => true,
                                        'y'      => true,
                                        'width'  => true,
                                        'height' => true,
                                        'rx'     => true,
                                        'fill'   => true,
                                    ],
                                    'line' => [
                                        'x1'     => true,
                                        'y1'     => true,
                                        'x2'     => true,
                                        'y2'     => true,
                                        'stroke' => true,
                                    ],
                                    'polyline' => [
                                        'points' => true,
                                        'fill'   => true,
                                        'stroke' => true,
                                    ],
                                    'polygon' => [
                                        'points' => true,
                                        'fill'   => true,
                                        'stroke' => true,
                                    ],
                                ];
                                echo wp_kses($service['icon'], $allowed_svg);
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($service['service_name'])) : ?>
                            <h3 class="text-xl font-bold mb-4 text-text-dark">
                                <?php echo esc_html($service['service_name']); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if (!empty($service['service_content'])) : ?>
                            <p class="text-text-dark/70 leading-relaxed">
                                <?php echo nl2br(esc_html($service['service_content'])); ?>
                            </p>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>



<!-- top-reason -->
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <?php
        $page_reason = get_page_by_path('top-reason');
        $reason_id = $page_reason ? $page_reason->ID : null;
        ?>
        <h2 class="font-serif text-3xl md:text-5xl font-bold mb-12 md:mb-16 text-text-dark">
            <?php echo get_field('salese', $reason_id); ?>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <?php
            $reasons = [
                ['num' => '01caption', 'title' => '01title', 'content' => '01contents'],
                ['num' => '02caption', 'title' => '02title', 'content' => '02contents'],
                ['num' => '03caption', 'title' => '03title', 'content' => '03contents'],
            ];
            foreach ($reasons as $r) : ?>
                <div>
                    <div class="font-serif text-5xl font-black text-secondary/30 mb-4 italic leading-none">
                        <?php echo get_field($r['num'], $reason_id); ?>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-text-dark mb-4">
                        <?php echo get_field($r['title'], $reason_id); ?>
                    </h3>
                    <p class="text-text-dark/80 leading-relaxed text-left md:text-center">
                        <?php echo nl2br(get_field($r['content'], $reason_id)); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- top-reason -->



<!-- top-flow -->
<section class="py-16 md:py-24 bg-light">
    <div class="max-w-4xl mx-auto px-6">
        <?php
        $page_flow = get_page_by_path('top-flow');
        $flow_id = $page_flow ? $page_flow->ID : null;
        ?>
        <div class="text-center mb-16 md:mb-20">
            <h2 class="font-serif text-3xl md:text-5xl font-bold inline-block border-b-4 border-primary pb-2 text-text-dark">
                <?php echo get_field('request_process_title', $flow_id); ?>
            </h2>
            <p class="font-serif text-2xl md:text-3xl font-bold mt-4 md:mt-6 text-primary">
                <?php echo get_field('request_process_sub_copy', $flow_id); ?>
            </p>
        </div>

        <div class="relative">
            <div class="absolute left-5 md:left-12 top-0 bottom-0 w-1 bg-primary/20 z-0"></div>

            <div class="space-y-12 md:space-y-8 relative z-10">
                <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <div class="relative flex flex-col md:flex-row items-start gap-4 md:gap-10 group">
                        <div class="flex-shrink-0 w-10 h-10 md:w-16 md:h-16 bg-white border-4 border-light text-primary flex items-center justify-center rounded-xl md:rounded-2xl shadow-sm z-20 transition-transform group-hover:scale-110">
                            <span class="text-lg md:text-2xl font-black italic"><?php echo sprintf('%02d', $i); ?></span>
                        </div>

                        <div class="flex-1 bg-white border border-secondary/10 p-6 md:p-10 rounded-3xl md:rounded-[3rem] shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                            <span class="absolute top-6 right-10 text-[10px] font-black text-secondary/10 tracking-[0.2em] uppercase hidden md:block">Step <?php echo sprintf('%02d', $i); ?></span>

                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary flex-shrink-0">
                                    <?php if ($i == 1): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                        </svg>
                                    <?php elseif ($i == 2): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                        </svg>
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <h3 class="text-xl md:text-2xl font-bold text-text-dark">
                                    <?php echo get_field('request_flow_title' . $i, $flow_id); ?>
                                </h3>
                            </div>
                            <p class="text-text-dark/80 leading-loose text-sm md:text-base">
                                <?php echo get_field('request_flow' . $i . '_text', $flow_id); ?>
                            </p>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>



    </div>
</section>
<!-- flow -->



<!--top-greeting-->
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center gap-12 md:gap-16">
        <?php
        $page_greeting = get_page_by_path('top-greeting');
        $greeting_id = $page_greeting ? $page_greeting->ID : null;
        $image = get_field('director_photo', $greeting_id);
        ?>
        <div class="md:w-2/5">
            <div class="relative">
                <div class="absolute -bottom-4 -right-4 w-full h-full border-2 border-accent rounded-2xl -z-10"></div>
                <img src="<?php echo esc_url($image['url']); ?>"
                    class="w-full h-[500px] object-cover rounded-2xl" alt="代表">
            </div>
        </div>
        <div class="md:w-3/5">
            <h2 class="font-serif text-3xl font-bold text-text-dark mb-8 leading-snug">
                <?php echo nl2br(get_field('director_head_copy', $greeting_id)); ?>
            </h2>
            <p class="font-serif text-text-dark/80 text-lg leading-loose mb-8">
                <?php echo nl2br(get_field('greeting_message', $greeting_id)); ?>
            </p>
            <div class="inline-block border-l-4 border-primary pl-4">
                <p class="font-serif text-2xl font-bold text-text-dark">
                    <?php echo get_field('director_name', $greeting_id); ?>
                </p>
            </div>
        </div>
    </div>
</section>





<!--contact-->

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-5xl mx-auto px-6">
        <div class="bg-primary rounded-[3rem] p-10 md:p-16 text-center text-white relative overflow-hidden shadow-2xl shadow-primary/30">

            <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-white/10 rounded-full"></div>

            <div class="relative z-10">
                <h2 class="font-serif text-2xl md:text-3xl font-bold mb-10">まずはお気軽にご相談ください</h2>
                <div class="flex flex-col md:flex-row gap-6 justify-center">


                    <a href="<?php echo home_url('/contact/'); ?>"
                        class="px-12 py-5 bg-cta text-white rounded-full font-black text-lg transition-all duration-300 hover:scale-105 shadow-lg">
                        お問い合わせフォーム
                    </a>
                    <?php
                    $page = get_page_by_path('settings');
                    if ($page) {
                        $phone = get_field('phone_number', $page->ID);
                        $phone_clean = str_replace('-', '', $phone);
                        $reception_hours = get_field('reception_hours', $page->ID);
                    }
                    ?>

                    <a href=" tel:<?php echo $phone_clean; ?>"
                        class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-12 py-5 rounded-full font-black text-lg transition-all border border-white/30 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span class="tracking-wider">
                            <?php echo $phone; ?>
                        </span>
                    </a>
                </div>
                <p class="mt-8 text-white/80 text-sm font-medium"><?php echo $reception_hours; ?></p>
            </div>
        </div>
    </div>
</section>


<!--contact-->



<!--news-->
<section class="py-12 md:py-20 bg-gray-50/50">
    <div class="max-w-5xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <h2 class="font-serif text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-8 h-[2px] bg-primary"></span>
                    お知らせ
                </h2>
                <p class="text-sm text-gray-500 mt-2 ml-11">News & Information</p>
            </div>
            <a href="<?php echo get_post_type_archive_link('news'); ?>" class="text-sm font-bold text-primary hover:underline underline-offset-4 decoration-2 ml-11 md:ml-0">
                一覧を見る →
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <?php
            $today = date('Ymd');

            $args = array(
                'post_type'      => 'news', // ★ここを 'post' から 'news' に変更
                'posts_per_page' => 3,
                'meta_query'     => array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'display_limit',
                        'value'   => '',
                        'compare' => '=', // 未入力の場合
                    ),
                    array(
                        'key'     => 'display_limit',
                        'compare' => 'NOT EXISTS', // フィールド自体が存在しない場合
                    ),
                    array(
                        'key'     => 'display_limit',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'NUMERIC', // 今日以降なら表示
                    ),
                ),
            );

            $news_query = new WP_Query($args);
            if ($news_query->have_posts()) :
                while ($news_query->have_posts()) : $news_query->the_post();
            ?>
                    <a href="<?php the_permalink(); ?>" class="group flex flex-col md:flex-row md:items-center gap-4 md:gap-8 p-6 border-b border-gray-100 last:border-none transition-colors hover:bg-gray-50">
                        <time class="text-sm font-medium text-gray-400 tabular-nums">
                            <?php echo get_the_date('Y.m.d'); ?>
                        </time>

                        <div class="flex items-center gap-3">
                            <span class="text-[10px] px-2 py-1 rounded-md bg-primary/10 text-primary font-bold tracking-wider">
                                INFO
                            </span>
                        </div>

                        <h3 class="flex-1 text-gray-800 font-medium group-hover:text-primary transition-colors line-clamp-1">
                            <?php the_title(); ?>
                        </h3>
                        <div class="hidden md:block text-gray-300 group-hover:translate-x-1 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p class="p-10 text-center text-gray-500 font-serif">現在、新しいお知らせはありません。</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php get_footer(); ?>