<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <link href="<?php echo get_template_directory_uri(); ?>/dist/output.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

    <?php
    // 「サイト設定」ページのIDを取得
    //$settings_page = get_page_by_path('site-settings-page');
    $settings_page = get_page_by_path('settings');
    $page_id = $settings_page ? $settings_page->ID : null;
    if ($page_id):
        // サイトのタイトル(ブラウザのタブ用)
        $site_title = get_field('site_title', $page_id);
        // キャッチフレーズ
        $site_catchphrase = get_field('site_catchphrase', $page_id);
        // サイトアイコン (ACFでイメージ、またはIDで取得)
        $site_icon = get_field('site_icon', $page_id);
    endif;

    if ($settings_page) :

        // サイトのタイトル（事務所名）
        $office_name = get_field('office_name', $settings_page->ID);
        // ACFのフィールド「site_icon」を取得
        $site_icon = get_field('site_icon', $settings_page->ID);

        if ($site_icon) :
            // 画像配列（Image Array）で取得している場合
            $icon_url = esc_url($site_icon['url']);
    ?>
            <!-- ブラウザのタブ用 (favicon) -->
            <link rel="icon" href="<?php echo $icon_url; ?>" sizes="any">

            <!-- iPhone / iPad ホーム画面用 -->
            <link rel="apple-touch-icon" href="<?php echo $icon_url; ?>">

            <!-- Windowsピン留め用 -->
            <meta name="msapplication-TileImage" content="<?php echo $icon_url; ?>">

    <?php
        endif;
    endif;
    ?>

    <title><?php echo esc_html($site_title); ?></title>
    <?php wp_head(); ?>
</head>




<body <?php body_class(); ?>>
    <!-- ////////////// Scroll Anchor ////////////// -->
    <div id="scroll-anchor"></div>
    <!-- ////////////// Scroll Anchor ////////////// -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex h-20 items-center justify-between">
                <div class="flex items-center gap-3">

                    <!-- ////// If you want to use the logo, here //////
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white font-bold text-xl">
                    S
                </div>
                    -->
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php endif; ?>

                    <div>
                        <h1 class="text-xl font-bold text-gray-900 leading-none">
                            <a href="<?php echo home_url(); ?>">
                                <?php echo $office_name; ?>
                            </a>
                        </h1>
                        <p class="text-[10px] text-gray-600 mt-1 tracking-tighter uppercase">
                            <?php echo $site_catchphrase; ?>
                        </p>
                    </div>
                </div>
                <?php
                $page = get_page_by_path('settings');
                if ($page) {
                    $phone = get_field('phone_number', $page->ID);
                    $phone_clean = str_replace('-', '', $phone);
                    $reception_hours = get_field('reception_hours', $page->ID);
                }
                ?>
                <div class="hidden md:flex items-center gap-8">
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 font-bold leading-none mb-1"><?php echo $reception_hours; ?></p>
                        <a href="tel:<?php echo $phone_clean; ?>"
                            class="flex items-center justify-end gap-1 text-xl font-bold text-primary leading-none tracking-tight hover:opacity-75 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.4-5.1-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1z" />
                            </svg>
                            <span class="font-din tracking-wider">
                                <?php
                                $page = get_page_by_path('settings');
                                if ($page) {
                                    echo get_field('phone_number', $page->ID);
                                }
                                ?>
                            </span>
                        </a>
                    </div>
                    <a href="<?php echo home_url('/contact/'); ?>"
                        class="inline-flex items-center px-6 py-3 text-white rounded-md font-bold text-sm transition-colors"
                        style="background-color: var(--color-cta);"
                        onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter=''">
                        お問い合わせフォーム
                    </a>
                </div>

                <div class="md:hidden">
                    <button id="mobile-menu-button" type="button" class="p-2 text-gray-500 hover:bg-gray-100 rounded-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>


            <nav class="hidden md:block border-t border-gray-100">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'global-menu',
                    'container'      => false,
                    'menu_class'     => 'flex w-full text-[13px] lg:text-sm font-bold text-gray-700',
                    'walker'         => new Custom_Nav_Walker(),
                ));
                ?>
            </nav>
        </div>

        <div id="mobile-menu"
            class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full left-0 z-50 shadow-2xl">

            <nav class="p-4 space-y-1 font-medium">

                <?php
                wp_nav_menu(array(
                    'theme_location' => 'global-menu',
                    'container'      => false,
                    'items_wrap'     => '%3$s', // ← ul消す（超重要）
                    'walker'         => new Mobile_Nav_Walker(),
                ));
                ?>
                <?php
                $page = get_page_by_path('settings');
                if ($page) {
                    $phone = get_field('phone_number', $page->ID);
                    $phone_clean = str_replace('-', '', $phone);
                    $reception_hours = get_field('reception_hours', $page->ID);
                }
                ?>

                <div class="pt-6 border-t border-gray-100 mt-4 text-center">
                    <p class="text-[11px] text-gray-500 font-bold mb-2">お電話でのご相談はこちら</p>

                    <a href="tel:<?php echo $phone_clean; ?>"
                        class="flex items-center justify-center gap-2 text-2xl font-bold text-primary hover:opacity-75 transition-opacity">

                        <!-- SVGそのままでOK -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.4-5.1-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1z" />
                        </svg>

                        <span class="tracking-wider"><?php echo $phone; ?></span>
                    </a>
                    <p class="text-[10px] text-gray-400 mt-2">
                        <?php echo $reception_hours; ?>
                    </p>
                </div>
            </nav>
        </div>
    </header>