<?php
function my_theme_setup()
{
    //register_nav_menus(['primary' => 'メインメニュー']);
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery']);

    register_nav_menus(array(
        'global-menu'  => 'グローバルメニュー',
        'footer-menu'  => 'フッターメニュー',
        'mobile-menu'  => 'スマホメニュー',
        'sidebar-menu' => 'サイドバーメニュー',
    ));
}
add_action('after_setup_theme', 'my_theme_setup');

/////////////////////////////
//   javascript
/////////////////////////////
function my_scripts()
{
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/js/main.js',
        array(),
        null,
        true // ← footerで読み込む（重要）
    );
    wp_enqueue_script(
        'scroll-handler-js',
        get_template_directory_uri() . '/js/scroll-handler.js',
        array(),
        filemtime(get_template_directory() . '/js/scroll-handler.js'), // 更新日時をVerにする
        true
    );
}
add_action('wp_enqueue_scripts', 'my_scripts');

function my_theme_widgets_init()
{
    register_sidebar([
        'name'          => 'サイドバー',
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="bg-gray-50 rounded-xl p-6 space-y-2">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="text-base font-semibold text-gray-700 mb-3">',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'my_theme_widgets_init');



/*これでブラウザが最優先で画像を取得します。*/
function preload_hero_image()
{

    if (is_front_page()) {

        $page = get_page_by_path('top-mainvisual');

        if ($page) {

            $hero_img = get_field('hero_img', $page->ID);

            if ($hero_img) {

                $image_url = wp_get_attachment_image_url($hero_img, 'full');

                echo '<link rel="preload" as="image" href="' . esc_url($image_url) . '">';
            }
        }
    }
}
add_action('wp_head', 'preload_hero_image', 1);




/////////////////////////////
//      tailwindcss
/////////////////////////////

function my_theme_enqueue()
{
    wp_enqueue_style('tailwind', get_template_directory_uri() . '/dist/output.css', [], null);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue');

/////////////////////////////
//      メールSMTP設定
/////////////////////////////

add_action('phpmailer_init', function ($phpmailer) {

    // 定義されていない、もしくは空の場合は何もしない
    if (!defined('SMTP_PASSWORD') || SMTP_PASSWORD === '') {
        return;
    }
    if (!defined('SENDER_MAIL') || SENDER_MAIL === '') {
        return;
    }

    $smtp_password = SMTP_PASSWORD;
    $sender_mail = SENDER_MAIL;
    $host_domain = HOST_DOMAIN;
    $from_name = FROM_NAME;

    $phpmailer->isSMTP();
    $phpmailer->Host       = $host_domain; // 初期ドメイン
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 465;                 // SMTPS
    $phpmailer->Username   = $sender_mail;
    $phpmailer->Password   = $smtp_password;
    $phpmailer->SMTPSecure = 'ssl';               // 465番の場合はssl

    // 送信元も自分のアドレスに固定（重要！）
    $phpmailer->From       = $sender_mail;
    $phpmailer->FromName   = $from_name;
});

class Custom_Nav_Walker extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        // 最後の要素判定
        $is_last = false;
        if (isset($args->menu->count)) {
            static $i = 0;
            $i++;
            if ($i === $args->menu->count) {
                $is_last = true;
                $i = 0;
            }
        }

        // 現在のページ判定
        $is_current = in_array('current-menu-item', (array) $item->classes)
            || in_array('current_page_item', (array) $item->classes)
            || in_array('current-menu-ancestor', (array) $item->classes); // 親メニューも対応

        // liクラス
        $li_class = 'flex-1';
        if (!$is_last) {
            $li_class .= ' border-r border-gray-100';
        }

        // aタグ：現在ページはテキスト色＋下線で強調
        $a_class = $is_current
            ? 'block py-4 text-center text-primary border-b-2 border-primary font-bold'
            : 'block py-4 text-center hover:text-primary hover:bg-gray-50 transition-colors';

        $output .= '<li class="' . $li_class . '">';
        $output .= '<a href="' . esc_url($item->url) . '" class="' . $a_class . '">';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }

    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= '</li>';
    }
}

/////////////////////////////
//   ナビメニュー（スマホ）
/////////////////////////////
class Mobile_Nav_Walker extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        // 現在のページ判定
        $is_current = in_array('current-menu-item', (array) $item->classes)
            || in_array('current_page_item', (array) $item->classes)
            || in_array('current-menu-ancestor', (array) $item->classes);

        // 現在ページは背景色＋テキスト色で強調
        $a_class = $is_current
            ? 'block px-4 py-3 rounded-lg bg-primary/10 text-primary font-bold'
            : 'block px-4 py-3 rounded-lg text-gray-700 hover:bg-light/40 hover:text-primary';

        $output .= '<a href="' . esc_url($item->url) . '" class="' . $a_class . '">';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {}
    function start_lvl(&$output, $depth = 0, $args = null) {}
    function end_lvl(&$output, $depth = 0, $args = null) {}
}

/////////////////////////////
//   フッターメニュー（PCスマホ）
/////////////////////////////

class Footer_Nav_Walker extends Walker_Nav_Menu
{

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {

        static $count = 0;
        $count++;

        $total = $args->menu->count ?? 0;

        $output .= '<li class="flex items-center">';
        $output .= '<a href="' . esc_url($item->url) . '" class="hover:text-primary transition-colors">';
        $output .= esc_html($item->title);
        $output .= '</a>';

        // 最後以外に区切り線
        if ($count < $total) {
            $output .= '<span class="mx-4 text-gray-300">|</span>';
        }

        $output .= '</li>';

        // リセット（次回ループ対策）
        if ($count === $total) {
            $count = 0;
        }
    }
}



register_block_pattern(
    'mytheme/heading-with-bar',
    [
        'title'   => '装飾付き見出し',
        'content' => '<!-- wp:group {"className":"flex items-center gap-4 mb-10"} -->
<div class="wp-block-group flex items-center gap-4 mb-10">
    <!-- wp:spacer {"className":"w-2 h-10 bg-primary rounded-full"} /-->
    <!-- wp:heading {"level":2,"className":"text-3xl font-bold"} -->
    <h2 class="text-3xl font-bold">ここに見出し</h2>
    <!-- /wp:heading -->
</div>
<!-- /wp:group -->',
    ]
);



/////////////////////////////
//   独自カスタムフィールド（代表者経歴用）
/////////////////////////////

function add_history_metabox()
{
    add_meta_box(
        'history_fields',
        '経歴',
        'history_callback',
        'page',
        'normal',
        'high'
    );
}
//add_action('add_meta_boxes', 'add_history_metabox');
add_action('add_meta_boxes', function () {
    global $post;
    if (isset($post) && get_post_field('post_name', $post->ID) === 'representative') {
        add_history_metabox();
    }
});

function history_callback($post)
{
    wp_nonce_field('history_nonce_action', 'history_nonce_field');

    $histories = get_post_meta($post->ID, '_histories', true);
    $histories = $histories ? json_decode($histories, true) : [];

?>
    <style>
        #history-container {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .history-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .history-row_child {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .history-row_child label {
            white-space: nowrap;
            font-weight: bold;
            font-size: 13px;
            color: #444;
        }

        .history-row_child input[name*="[year]"] {
            width: 160px;
        }

        .history-row_child input[name*="[content]"] {
            width: 360px;
        }

        .remove-history {
            margin-left: auto;
            color: #fff;
            background: #cc0000;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
        }

        .remove-history:hover {
            background: #aa0000;
        }

        #add-history {
            margin-top: 12px;
            padding: 6px 14px;
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #add-history:hover {
            background: #135e96;
        }
    </style>
    <?php

    echo '<div id="history-container">';
    if (!empty($histories)) {
        foreach ($histories as $key => $item) {
            echo '<div class="history-row">';

            echo '<div class="history-row_child">';
            echo '<label>年</label>';
            echo '<input type="text" name="histories[' . $key . '][year]" value="' . esc_attr($item['year']) . '" placeholder="例）2002年（平成14年）" />';
            echo '</div>';

            echo '<div class="history-row_child">';
            echo '<label>内容</label>';
            echo '<input type="text" name="histories[' . $key . '][content]" value="' . esc_attr($item['content']) . '" placeholder="例）○○大学法学部 卒業" />';
            echo '</div>';

            echo '<button type="button" class="remove-history">削除</button>';
            echo '</div>';
        }
    }
    echo '</div>';

    echo '<button type="button" id="add-history">フィールド追加</button>';

    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('history-container');
            const addButton = document.getElementById('add-history');

            addButton.addEventListener('click', function() {
                const count = container.children.length;
                const fieldHTML = `
                    <div class="history-row">
                        <div class="history-row_child">
                            <label>年</label>
                            <input type="text" name="histories[${count}][year]" placeholder="例）2002年（平成14年）" />
                        </div>
                        <div class="history-row_child">
                            <label>内容</label>
                            <input type="text" name="histories[${count}][content]" placeholder="例）○○大学法学部 卒業" />
                        </div>
                        <button type="button" class="remove-history">削除</button>
                    </div>`;
                container.insertAdjacentHTML('beforeend', fieldHTML);
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-history')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
<?php
}

function save_history_meta_box($post_id)
{
    if (
        !isset($_POST['history_nonce_field']) ||
        !wp_verify_nonce($_POST['history_nonce_field'], 'history_nonce_action') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (isset($_POST['histories'])) {
        $items = [];
        foreach ($_POST['histories'] as $item) {
            $items[] = [
                'year'    => sanitize_text_field($item['year']),
                'content' => sanitize_text_field($item['content']),
            ];
        }
        update_post_meta($post_id, '_histories', json_encode($items, JSON_UNESCAPED_UNICODE));
    } else {
        delete_post_meta($post_id, '_histories');
    }
}
add_action('save_post', 'save_history_meta_box');



/////////////////////////////
//   独自カスタムフィールド（FAQ よくある質問）
/////////////////////////////

function add_faq_metabox()
{
    add_meta_box(
        'faq_fields',
        'FAQ',
        'faq_callback',
        'page',
        'normal',
        'high'
    );
}
//add_action('add_meta_boxes', 'add_faq_metabox');
add_action('add_meta_boxes', function () {
    global $post;
    if (isset($post) && get_post_field('post_name', $post->ID) === 'faq') {
        add_faq_metabox();
    }
});

function faq_callback($post)
{
    wp_nonce_field('faq_nonce_action', 'faq_nonce_field');

    $faqs = get_post_meta($post->ID, '_faqs', true);
    $faqs = $faqs ? json_decode($faqs, true) : [];

?>
    <style>
        #faq-container {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .faq-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .faq-row_child {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .faq-row_child label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
        }

        .faq-row_child input[name*="[category]"] {
            width: 200px;
        }

        .faq-row_child textarea[name*="[question]"],
        .faq-row_child textarea[name*="[answer]"] {
            width: 500px;
            height: 100px;
            resize: vertical;
        }

        .remove-faq {
            margin-left: auto;
            color: #fff;
            background: #cc0000;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .remove-faq:hover {
            background: #aa0000;
        }

        #add-faq {
            margin-top: 12px;
            padding: 6px 14px;
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #add-faq:hover {
            background: #135e96;
        }
    </style>
    <?php

    echo '<div id="faq-container">';
    if (!empty($faqs)) {
        foreach ($faqs as $key => $item) {
            echo '<div class="faq-row">';

            echo '<div class="faq-row_child">';
            echo '<label>カテゴリー</label>';
            echo '<input type="text" name="faqs[' . $key . '][category]" value="' . esc_attr($item['category']) . '" placeholder="例）料金について" />';
            echo '</div>';

            echo '<div class="faq-row_child">';
            echo '<label>質問</label>';
            echo '<textarea name="faqs[' . $key . '][question]" placeholder="例）相談は無料ですか？">' . esc_textarea($item['question']) . '</textarea>';
            echo '</div>';

            echo '<div class="faq-row_child">';
            echo '<label>回答</label>';
            echo '<textarea name="faqs[' . $key . '][answer]" placeholder="例）はい、初回相談は無料です。">' . esc_textarea($item['answer']) . '</textarea>';
            echo '</div>';

            echo '<button type="button" class="remove-faq">削除</button>';
            echo '</div>';
        }
    }
    echo '</div>';

    echo '<button type="button" id="add-faq">フィールド追加</button>';

    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('faq-container');
            const addButton = document.getElementById('add-faq');

            addButton.addEventListener('click', function() {
                const count = container.children.length;
                const fieldHTML = `
                    <div class="faq-row">
                        <div class="faq-row_child">
                            <label>カテゴリー</label>
                            <input type="text" name="faqs[${count}][category]" placeholder="例）料金について" />
                        </div>
                        <div class="faq-row_child">
                            <label>質問</label>
                            <textarea name="faqs[${count}][question]" placeholder="例）相談は無料ですか？"></textarea>
                        </div>
                        <div class="faq-row_child">
                            <label>回答</label>
                            <textarea name="faqs[${count}][answer]" placeholder="例）はい、初回相談は無料です。"></textarea>
                        </div>
                        <button type="button" class="remove-faq">削除</button>
                    </div>`;
                container.insertAdjacentHTML('beforeend', fieldHTML);
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-faq')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
<?php
}

function save_faq_meta_box($post_id)
{
    if (
        !isset($_POST['faq_nonce_field']) ||
        !wp_verify_nonce($_POST['faq_nonce_field'], 'faq_nonce_action') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (isset($_POST['faqs'])) {
        $items = [];
        foreach ($_POST['faqs'] as $item) {
            $items[] = [
                'category' => sanitize_text_field($item['category']),
                'question' => sanitize_textarea_field($item['question']),
                'answer'   => sanitize_textarea_field($item['answer']),
            ];
        }
        update_post_meta($post_id, '_faqs', wp_slash(json_encode($items, JSON_UNESCAPED_UNICODE)));
    } else {
        delete_post_meta($post_id, '_faqs');
    }
}
add_action('save_post', 'save_faq_meta_box');



/////////////////////////////
//   独自カスタムフィールド（業務案内）
/////////////////////////////

// メタボックス登録
add_action('add_meta_boxes', function () {
    global $post;
    if (isset($post) && get_post_field('post_name', $post->ID) === 'business') {
        add_meta_box(
            'price_fields',
            '業務案内',
            'price_callback',
            'page',
            'normal',
            'high'
        );
    }
});

function price_callback($post)
{
    wp_nonce_field('price_nonce_action', 'price_nonce_field');

    $sections = get_post_meta($post->ID, '_price_sections', true);
    $sections = is_array($sections) ? $sections : [];
?>
    <style>
        #price-section-container {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .price-section {
            border: 1px solid #c3c4c7;
            border-radius: 6px;
            margin-bottom: 16px;
            background: #f9f9f9;
            padding: 16px;
        }

        .price-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .price-section-header input {
            width: 100%;
            font-size: 15px;
            font-weight: normal;
            margin-right: 12px;
        }

        .price-section-desc textarea,
        .price-section-warning textarea {
            width: 100%;
            height: 80px;
            resize: vertical;
            margin-bottom: 12px;
        }

        .price-section-desc label,
        .price-section-warning label,
        .price-rows-label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
            display: block;
            margin-bottom: 4px;
        }

        .price-section-warning {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #ccc;
        }

        .price-section-warning label {
            color: #444;
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .price-row input[name*="[item]"] {
            width: 360px;
        }

        .price-row input[name*="[price]"] {
            width: 160px;
        }

        .remove-price-row,
        .remove-price-section {
            color: #fff;
            background: #cc0000;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
        }

        .remove-price-row:hover,
        .remove-price-section:hover {
            background: #aa0000;
        }

        .add-price-row {
            margin-top: 8px;
            padding: 4px 12px;
            background: #6c757d;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .add-price-row:hover {
            background: #545b62;
        }

        #add-price-section {
            margin-top: 4px;
            padding: 6px 14px;
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #add-price-section:hover {
            background: #135e96;
        }
    </style>

    <div id="price-section-container">
        <?php foreach ($sections as $si => $section) : ?>
            <div class="price-section" data-section-index="<?php echo $si; ?>">

                <!-- 見出し -->
                <div class="price-section-header">
                    <input type="text"
                        name="price_sections[<?php echo $si; ?>][title]"
                        value="<?php echo esc_attr($section['title']); ?>"
                        placeholder="例）相続手続／相続対策" />
                    <button type="button" class="remove-price-section">セクション削除</button>
                </div>

                <!-- 説明文 -->
                <div class="price-section-desc">
                    <label>説明文</label>
                    <textarea name="price_sections[<?php echo $si; ?>][description]"
                        placeholder="サービスの説明を入力"><?php echo esc_textarea($section['description']); ?></textarea>
                </div>

                <!-- 料金項目 -->
                <div class="price-rows-wrap">
                    <span class="price-rows-label">料金項目</span>
                    <div class="price-rows-container">
                        <?php foreach ($section['rows'] as $ri => $row) : ?>
                            <div class="price-row">
                                <input type="text"
                                    name="price_sections[<?php echo $si; ?>][rows][<?php echo $ri; ?>][item]"
                                    value="<?php echo esc_attr($row['item']); ?>"
                                    placeholder="例）相続調査" />
                                <input type="text"
                                    name="price_sections[<?php echo $si; ?>][rows][<?php echo $ri; ?>][price]"
                                    value="<?php echo esc_attr($row['price']); ?>"
                                    placeholder="例）20,000円～" />
                                <button type="button" class="remove-price-row">削除</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="add-price-row">料金行を追加</button>
                </div>

                <!-- 注意書き -->
                <div class="price-section-warning">
                    <label>注釈(税込み税抜きなど)</label>
                    <textarea name="price_sections[<?php echo $si; ?>][warning]"
                        placeholder="例）別途実費（交通費・証明書取得費用等）が発生する場合があります。"><?php echo esc_textarea($section['warning'] ?? ''); ?></textarea>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" id="add-price-section">セクションを追加</button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionContainer = document.getElementById('price-section-container');
            const addSectionBtn = document.getElementById('add-price-section');

            // セクション追加
            addSectionBtn.addEventListener('click', function() {
                const si = sectionContainer.children.length;
                const sectionHTML = `
                <div class="price-section" data-section-index="${si}">
                    <div class="price-section-header">
                        <input type="text" name="price_sections[${si}][title]" placeholder="例）相続手続／相続対策" />
                        <button type="button" class="remove-price-section">セクション削除</button>
                    </div>
                    <div class="price-section-desc">
                        <label>説明文</label>
                        <textarea name="price_sections[${si}][description]" placeholder="サービスの説明を入力"></textarea>
                    </div>
                    <div class="price-rows-wrap">
                        <span class="price-rows-label">料金項目</span>
                        <div class="price-rows-container"></div>
                        <button type="button" class="add-price-row">料金行を追加</button>
                    </div>
                    <div class="price-section-warning">
                        <label>⚠ 注意書き（warning）</label>
                        <textarea name="price_sections[${si}][warning]" placeholder="例）別途実費（交通費・証明書取得費用等）が発生する場合があります。"></textarea>
                    </div>
                </div>`;
                sectionContainer.insertAdjacentHTML('beforeend', sectionHTML);
            });

            // セクション削除・料金行追加・料金行削除（イベント委譲）
            sectionContainer.addEventListener('click', function(e) {

                // セクション削除
                if (e.target.classList.contains('remove-price-section')) {
                    e.target.closest('.price-section').remove();
                    reindexSections();
                }

                // 料金行追加
                if (e.target.classList.contains('add-price-row')) {
                    const section = e.target.closest('.price-section');
                    const si = [...sectionContainer.children].indexOf(section);
                    const rowContainer = section.querySelector('.price-rows-container');
                    const ri = rowContainer.children.length;
                    const rowHTML = `
                    <div class="price-row">
                        <input type="text" name="price_sections[${si}][rows][${ri}][item]" placeholder="例）相続調査" />
                        <input type="text" name="price_sections[${si}][rows][${ri}][price]" placeholder="例）20,000円～" />
                        <button type="button" class="remove-price-row">削除</button>
                    </div>`;
                    rowContainer.insertAdjacentHTML('beforeend', rowHTML);
                }

                // 料金行削除
                if (e.target.classList.contains('remove-price-row')) {
                    e.target.closest('.price-row').remove();
                }
            });

            // セクション削除後にインデックスを振り直す
            function reindexSections() {
                [...sectionContainer.children].forEach(function(section, si) {
                    section.querySelectorAll('input, textarea').forEach(function(el) {
                        el.name = el.name.replace(/price_sections\[\d+\]/, `price_sections[${si}]`);
                    });
                    [...section.querySelectorAll('.price-rows-container')].forEach(function(rowContainer) {
                        [...rowContainer.children].forEach(function(row, ri) {
                            row.querySelectorAll('input').forEach(function(el) {
                                el.name = el.name.replace(/\[rows\]\[\d+\]/, `[rows][${ri}]`);
                            });
                        });
                    });
                });
            }
        });
    </script>
<?php
}

function save_price_meta_box($post_id)
{
    if (
        !isset($_POST['price_nonce_field']) ||
        !wp_verify_nonce($_POST['price_nonce_field'], 'price_nonce_action') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }
    //error_log(print_r($_POST['price_sections'], true));

    if (isset($_POST['price_sections'])) {
        $sections = [];
        foreach ($_POST['price_sections'] as $section) {
            $rows = [];
            if (!empty($section['rows'])) {
                foreach ($section['rows'] as $row) {
                    $rows[] = [
                        'item'  => sanitize_text_field($row['item']),
                        'price' => sanitize_text_field($row['price']),
                    ];
                }
            }

            $sections[] = [
                'title' => sanitize_text_field($section['title']),
                'description' => sanitize_textarea_field($section['description']),
                'rows' => $rows,
                'warning' => sanitize_textarea_field($section['warning'] ?? ''),
            ];
        }

        update_post_meta(
            $post_id,
            '_price_sections',
            $sections
        );
    } else {
        delete_post_meta($post_id, '_price_sections');
    }
}
add_action('save_post', 'save_price_meta_box');


/////////////////////////////
//   独自カスタムフィールド（課題提起）
/////////////////////////////


// メタボックス登録
add_action('add_meta_boxes', function () {
    global $post;
    if (isset($post) && get_post_field('post_name', $post->ID) === 'top-problem') {
        add_meta_box(
            'problem_fields',
            '課題提起',
            'problem_callback',
            'page',
            'normal',
            'high'
        );
    }
});

function problem_callback($post)
{
    wp_nonce_field('problem_nonce_action', 'problem_nonce_field');

    $data = get_post_meta($post->ID, '_problem_section', true);
    $data = $data ? json_decode($data, true) : [];

    $head_copy   = $data['head_copy']   ?? '';
    $head_copy2  = $data['head_copy2']  ?? '';
    $worries     = $data['worries']     ?? [];
    $omakase_head = $data['omakase_head'] ?? '';
    $omakase_body = $data['omakase_body'] ?? '';

?>
    <style>
        .problem-wrap {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .problem-field-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .problem-field-row label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
        }

        .problem-field-row input[type="text"] {
            width: 600px;
        }

        .problem-field-row textarea {
            width: 600px;
            height: 100px;
            resize: vertical;
        }

        .problem-section-title {
            font-size: 13px;
            font-weight: bold;
            color: #2271b1;
            margin: 16px 0 4px;
            padding: 4px 8px;
            background: #f0f6fc;
            border-left: 3px solid #2271b1;
        }

        /* お悩み実例の繰り返し行 */
        #worry-container {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .worry-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .worry-row_child {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .worry-row_child label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
        }

        .worry-row_child input[name*="[worry_title]"] {
            width: 200px;
        }

        .worry-row_child textarea[name*="[worry_content]"] {
            width: 380px;
            height: 80px;
            resize: vertical;
        }

        .remove-worry {
            margin-left: auto;
            color: #fff;
            background: #cc0000;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .remove-worry:hover {
            background: #aa0000;
        }

        #add-worry {
            margin-top: 10px;
            padding: 5px 12px;
            /*background: #6c757d;*/
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            display: inline-block;
            /* 追加 */
            width: auto;
            /* 追加 */
            width: fit-content;
            /* 追加 */
        }

        #add-worry:hover {
            /*background: #545b62;*/
            background: #135e96;
        }
    </style>

    <div class="problem-wrap">

        <!-- セクションヘッドコピー -->
        <div class="problem-field-row">
            <label>セクションヘッドコピー</label>
            <input type="text"
                name="problem[head_copy]"
                value="<?php echo esc_attr($head_copy); ?>"
                placeholder="例）あなたはこんなお悩みを抱えていませんか？" />
        </div>

        <!-- セクションヘッドコピー2 -->
        <div class="problem-field-row">
            <label>セクションヘッドコピー2</label>
            <input type="text"
                name="problem[head_copy2]"
                value="<?php echo esc_attr($head_copy2); ?>"
                placeholder="例）相続に関する不安を、専門家が一つひとつ紐解きます。" />
        </div>

        <!-- お悩み実例（繰り返し） -->
        <div class="problem-field-row">
            <div class="problem-section-title">お悩み実例（繰り返し登録）</div>
            <div id="worry-container">
                <?php foreach ($worries as $wi => $worry) : ?>
                    <div class="worry-row">
                        <div class="worry-row_child">
                            <label>お悩み実例タイトル</label>
                            <input type="text"
                                name="problem[worries][<?php echo $wi; ?>][worry_title]"
                                value="<?php echo esc_attr($worry['worry_title']); ?>"
                                placeholder="例）相続人が誰かわからない" />
                        </div>
                        <div class="worry-row_child">
                            <label>お悩み実例内容</label>
                            <textarea
                                name="problem[worries][<?php echo $wi; ?>][worry_content]"
                                placeholder="例）親族関係が複雑で..."><?php echo esc_textarea($worry['worry_content']); ?></textarea>
                        </div>
                        <button type="button" class="remove-worry">削除</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-worry">フィールド追加</button>
        </div>

        <!-- おまかせくださいヘッドコピー -->
        <div class="problem-field-row">
            <label>おまかせくださいヘッドコピー</label>
            <input type="text"
                name="problem[omakase_head]"
                value="<?php echo esc_attr($omakase_head); ?>"
                placeholder="例）そのお悩み、ぜひおまかせください" />
        </div>

        <!-- おまかせください本文 -->
        <div class="problem-field-row">
            <label>おまかせください本文</label>
            <textarea
                name="problem[omakase_body]"
                placeholder="例）当事務所では..."><?php echo esc_textarea($omakase_body); ?></textarea>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('worry-container');
            const addButton = document.getElementById('add-worry');

            // お悩み実例追加
            addButton.addEventListener('click', function() {
                const wi = container.children.length;
                const rowHTML = `
                <div class="worry-row">
                    <div class="worry-row_child">
                        <label>お悩み実例タイトル</label>
                        <input type="text"
                            name="problem[worries][${wi}][worry_title]"
                            placeholder="例）相続人が誰かわからない" />
                    </div>
                    <div class="worry-row_child">
                        <label>お悩み実例内容</label>
                        <textarea
                            name="problem[worries][${wi}][worry_content]"
                            placeholder="例）親族関係が複雑で..."></textarea>
                    </div>
                    <button type="button" class="remove-worry">削除</button>
                </div>`;
                container.insertAdjacentHTML('beforeend', rowHTML);
            });

            // お悩み実例削除
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-worry')) {
                    e.target.closest('.worry-row').remove();
                }
            });
        });
    </script>
<?php
}

function save_problem_meta_box($post_id)
{
    if (
        !isset($_POST['problem_nonce_field']) ||
        !wp_verify_nonce($_POST['problem_nonce_field'], 'problem_nonce_action') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (isset($_POST['problem'])) {
        $p = $_POST['problem'];

        $worries = [];
        if (!empty($p['worries'])) {
            foreach ($p['worries'] as $worry) {
                $worries[] = [
                    'worry_title'   => sanitize_text_field($worry['worry_title']),
                    'worry_content' => wp_kses_post(wp_unslash($worry['worry_content'])),

                ];
            }
        }

        $data = [
            'head_copy'    => sanitize_text_field($p['head_copy']),
            'head_copy2'   => sanitize_text_field($p['head_copy2']),
            'worries'      => $worries,
            'omakase_head' => sanitize_text_field($p['omakase_head']),
            'omakase_body' => wp_kses_post(wp_unslash($p['omakase_body'])),
        ];
        /*改行文字rnに対応*/
        update_post_meta($post_id, '_problem_section', wp_slash(json_encode($data, JSON_UNESCAPED_UNICODE)));
    } else {
        delete_post_meta($post_id, '_problem_section');
    }
}
add_action('save_post', 'save_problem_meta_box');


/////////////////////////////////////////////////
/////  サービスセクション(top-services)
/////////////////////////////////////////////////


// メタボックス登録
add_action('add_meta_boxes', function () {
    global $post;
    if (isset($post) && get_post_field('post_name', $post->ID) === 'top-services') {
        add_meta_box(
            'services_fields',
            'サービス',
            'services_callback',
            'page',
            'normal',
            'high'
        );
    }
});

function services_callback($post)
{
    wp_nonce_field('services_nonce_action', 'services_nonce_field');

    $data = get_post_meta($post->ID, '_services_section', true);
    $data = $data ? json_decode($data, true) : [];

    $section_title    = $data['section_title'] ?? '';
    $section_subtitle = $data['section_subtitle'] ?? ''; // 追加
    $services         = $data['services']      ?? [];

?>
    <style>
        .services-wrap {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .services-field-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .services-field-row label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
        }

        .services-field-row input[type="text"] {
            width: 600px;
        }

        .services-section-title {
            font-size: 13px;
            font-weight: bold;
            color: #2271b1;
            margin: 16px 0 4px;
            padding: 4px 8px;
            background: #f0f6fc;
            border-left: 3px solid #2271b1;
        }

        #service-container {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .service-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .service-row_child {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .service-row_child label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
        }

        /* SVGテキストエリア */
        .service-row_child textarea[name*="[icon]"] {
            width: 400px;
            height: 80px;
            resize: vertical;
            font-size: 11px;
            font-family: monospace;
            color: #555;
        }

        .service-row_child input[name*="[service_name]"] {
            width: 160px;
        }

        .service-row_child textarea[name*="[service_content]"] {
            width: 300px;
            height: 80px;
            resize: vertical;
        }

        .remove-service {
            margin-left: auto;
            color: #fff;
            background: #cc0000;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .remove-service:hover {
            background: #aa0000;
        }

        #add-service {
            margin-top: 10px;
            padding: 5px 12px;
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            width: fit-content;
        }

        #add-service:hover {
            background: #135e96;
        }
    </style>

    <div class="services-wrap">

        <!-- セクションタイトル -->
        <div class="services-field-row">
            <label>セクションタイトル</label>
            <input type="text"
                name="services[section_title]"
                value="<?php echo esc_attr($section_title); ?>"
                placeholder="例）サービス内容" />
        </div>

        <!-- セクションサブタイトル（追加分） -->
        <div class="services-field-row">
            <label>セクションサブタイトル</label>
            <input type="text"
                name="services[section_subtitle]"
                value="<?php echo esc_attr($section_subtitle); ?>"
                placeholder="例）あなたの悩みに私達がお役に立てます" />
        </div>

        <!-- サービス繰り返し -->
        <div class="services-field-row">
            <div class="services-section-title">サービス項目（繰り返し登録）</div>
            <div id="service-container">
                <?php foreach ($services as $si => $service) : ?>
                    <div class="service-row">
                        <div class="service-row_child">
                            <label>アイコン（SVG）<a href="https://heroicons.com/" target="_blank">heroicons.com</a></label>
                            <textarea
                                name="services[services][<?php echo $si; ?>][icon]"
                                placeholder="heroicons.comのSVGコードを貼り付け"><?php echo esc_textarea($service['icon']); ?></textarea>
                        </div>
                        <div class="service-row_child">
                            <label>サービス名</label>
                            <input type="text"
                                name="services[services][<?php echo $si; ?>][service_name]"
                                value="<?php echo esc_attr($service['service_name']); ?>"
                                placeholder="例）相続手続き" />
                        </div>
                        <div class="service-row_child">
                            <label>サービス内容</label>
                            <textarea
                                name="services[services][<?php echo $si; ?>][service_content]"
                                placeholder="例）相続人の調査から遺産分割協議書の作成まで..."><?php echo esc_textarea($service['service_content']); ?></textarea>
                        </div>
                        <button type="button" class="remove-service">削除</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-service">フィールド追加</button>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('service-container');
            const addButton = document.getElementById('add-service');

            addButton.addEventListener('click', function() {
                const si = container.children.length;
                const rowHTML = `
                <div class="service-row">
                    <div class="service-row_child">
                        <label>アイコン（SVG）</label>
                        <textarea
                            name="services[services][${si}][icon]"
                            placeholder="heroicons.comのSVGコードを貼り付け"></textarea>
                    </div>
                    <div class="service-row_child">
                        <label>サービス名</label>
                        <input type="text"
                            name="services[services][${si}][service_name]"
                            placeholder="例）相続手続き" />
                    </div>
                    <div class="service-row_child">
                        <label>サービス内容</label>
                        <textarea
                            name="services[services][${si}][service_content]"
                            placeholder="例）相続人の調査から遺産分割協議書の作成まで..."></textarea>
                    </div>
                    <button type="button" class="remove-service">削除</button>
                </div>`;
                container.insertAdjacentHTML('beforeend', rowHTML);
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-service')) {
                    e.target.closest('.service-row').remove();
                }
            });
        });
    </script>
<?php
}

function save_services_meta_box($post_id)
{
    if (
        !isset($_POST['services_nonce_field']) ||
        !wp_verify_nonce($_POST['services_nonce_field'], 'services_nonce_action') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (isset($_POST['services'])) {
        $s = $_POST['services'];

        $services = [];
        if (!empty($s['services'])) {
            foreach ($s['services'] as $service) {
                $icon_raw = isset($service['icon']) ? wp_unslash($service['icon']) : '';
                $services[] = [
                    'icon'            => $icon_raw,
                    'service_name'    => sanitize_text_field($service['service_name']),
                    'service_content' => wp_kses_post(wp_unslash($service['service_content'])),
                ];
            }
        }

        $data = [
            'section_title'    => sanitize_text_field($s['section_title']),
            'section_subtitle' => sanitize_text_field($s['section_subtitle'] ?? ''), // 保存処理の追加
            'services'         => $services,
        ];

        update_post_meta($post_id, '_services_section', wp_slash(json_encode($data, JSON_UNESCAPED_UNICODE)));
    } else {
        delete_post_meta($post_id, '_services_section');
    }
}
add_action('save_post', 'save_services_meta_box');







/////////////////////////////////////////////////
///// ACF用共通スタイル
/////////////////////////////////////////////////

function acf_admin_style()
{
    $screen = get_current_screen();
    if (!$screen || $screen->base !== 'post') return;

?>
    <style>
        /* フィールドラベル */
        .acf-field .acf-label label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
        }

        /* サブテキスト（説明文） */
        .acf-field .acf-label p.description {
            font-size: 12px;
            color: #888;
            margin-top: 2px;
        }

        /* テキスト・URL・メール入力 */
        .acf-field input[type="text"],
        .acf-field input[type="url"],
        .acf-field input[type="email"],
        .acf-field input[type="number"] {
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 13px;
            color: #1d2327;
        }

        /* テキストエリア */
        .acf-field textarea {
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 13px;
            color: #1d2327;
            resize: vertical;
        }

        /* フォーカス時 */
        .acf-field input:focus,
        .acf-field textarea:focus,
        .acf-field select:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }

        /* 画像アップロードボタン */
        .acf-field .acf-image-uploader .acf-button,
        .acf-field .acf-image-uploader a.acf-button {
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px 12px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .acf-field .acf-image-uploader .acf-button:hover,
        .acf-field .acf-image-uploader a.acf-button:hover {
            background: #135e96;
            color: #fff;
        }

        /* フィールド間の区切り線 */
        .acf-field {
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0 !important;
        }

        /* ACFメタボックスタイトル */
        .acf-postbox .postbox-header h2,
        .acf-postbox .postbox-header .hndle {
            font-size: 14px;
            font-weight: bold;
            color: #1d2327;
            border-bottom: 2px solid #2271b1;
            padding-bottom: 8px;
        }

        /* カスタムメタボックスタイトル（経歴・FAQ・料金） */
        #history_fields .postbox-header h2,
        #faq_fields .postbox-header h2,
        #price_fields .postbox-header h2 {
            font-size: 14px;
            font-weight: bold;
            color: #1d2327;
            border-bottom: 2px solid #2271b1;
            padding-bottom: 8px;
        }
    </style>
<?php
}
add_action('admin_head', 'acf_admin_style');


/////////////////////////////////////////////////
//////   管理画面メニュー登録
/////////////////////////////////////////////////
function add_custom_admin_menu()
{
    add_menu_page(
        'ページ設定',
        'ページ設定',
        'edit_pages',
        'site-settings',
        '__return_null',
        'dashicons-admin-settings',
        25
    );

    add_submenu_page(
        'site-settings',
        'トップページ設定',
        'トップページ設定',
        'edit_pages',
        'site-settings',
        'render_site_settings_placeholder'
    );

    $top_pages = [
        'site-settings-top-1' => ['label' => '1. メインビジュアル', 'slug' => 'top-mainvisual'],
        'site-settings-top-2' => ['label' => '2. 課題提起',         'slug' => 'top-problem'],
        'site-settings-top-3' => ['label' => '3. サービス',         'slug' => 'top-services'],
        'site-settings-top-4' => ['label' => '4. 選ばれる理由',     'slug' => 'top-reason'],
        'site-settings-top-5' => ['label' => '5. ご依頼の流れ',     'slug' => 'top-flow'],
        'site-settings-top-6' => ['label' => '6. 代表挨拶',         'slug' => 'top-greeting'],
    ];

    foreach ($top_pages as $menu_slug => $item) {
        add_submenu_page(
            'site-settings',
            $item['label'],
            $item['label'],
            'edit_pages',
            $menu_slug,
            'render_site_settings_placeholder'
        );
    }


    add_submenu_page(
        'site-settings',
        '代表紹介',
        '代表紹介',
        'edit_pages',
        'site-settings-representative',
        'render_site_settings_placeholder'
    );
    add_submenu_page(
        'site-settings',
        '業務案内',
        '業務案内',
        'edit_pages',
        'site-settings-business',
        'render_site_settings_placeholder'
    );
    add_submenu_page(
        'site-settings',
        'FAQ',
        'FAQ',
        'edit_pages',
        'site-settings-faq',
        'render_site_settings_placeholder'
    );
    add_submenu_page(
        'site-settings',
        'プライバシーポリシー',
        'プライバシーポリシー',
        'edit_pages',
        'site-settings-privacy',
        'render_site_settings_placeholder'
    );

    // 【追加】メール設定
    add_submenu_page(
        'site-settings',
        'メール設定',
        'メール設定',
        'edit_pages',
        'site-settings-mail',
        'render_site_settings_placeholder'
    );

    // 【追加】サンクスページ
    add_submenu_page(
        'site-settings',
        'サンクスページ',
        'サンクスページ',
        'edit_pages',
        'site-settings-thanks',
        'render_site_settings_placeholder'
    );

    // 【追加】トップページコンタクト
    add_submenu_page(
        'site-settings',
        'トップページコンタクト',
        'トップページコンタクト',
        'edit_pages',
        'site-settings-top-contact',
        'render_site_settings_placeholder'
    );
    // 【追加】トップページフッター
    add_submenu_page(
        'site-settings',
        'フッター',
        'フッター',
        'edit_pages',
        'site-settings-top-footer',
        'render_site_settings_placeholder'
    );


    add_submenu_page(
        'site-settings',
        'ブログ投稿',
        'ブログ投稿',
        'edit_pages',
        'site-settings-blog',
        'render_site_settings_placeholder'
    );
}
add_action('admin_menu', 'add_custom_admin_menu');

function render_site_settings_placeholder()
{
    echo '<div class="wrap"><p>リダイレクト中...</p></div>';
}

function handle_site_settings_redirects()
{
    if (!is_admin() || !current_user_can('edit_pages')) {
        return;
    }

    $page = isset($_GET['page']) ? $_GET['page'] : '';

    if ($page === 'site-settings') {
        wp_safe_redirect(admin_url('admin.php?page=site-settings-top-1'));
        exit;
    }

    if ($page === 'site-settings-blog') {
        wp_safe_redirect(admin_url('edit.php'));
        exit;
    }

    $page_map = [
        //'site-settings-settings' => 'settings',
        'site-settings-top-1'          => 'top-mainvisual',
        'site-settings-top-2'          => 'top-problem',
        'site-settings-top-3'          => 'top-services',
        'site-settings-top-4'          => 'top-reason',
        'site-settings-top-5'          => 'top-flow',
        'site-settings-top-6'          => 'top-greeting',
        //'site-settings-common_settings' => 'common_settings',
        'site-settings-representative' => 'representative',
        'site-settings-business'       => 'business',
        'site-settings-faq'            => 'faq',
        'site-settings-privacy'        => 'privacy-policy',
        // 【追加】リダイレクト先のスラッグ設定
        'site-settings-mail'           => 'mail-settings',
        'site-settings-top-contact'    => 'top-contact',
        'site-settings-top-footer'    => 'top-footer',
        'site-settings-thanks'         => 'thanks',
    ];

    if (!array_key_exists($page, $page_map)) {
        return;
    }

    $slug = $page_map[$page];
    $post = get_page_by_path($slug);

    if ($post) {
        wp_safe_redirect(admin_url('post.php?post=' . $post->ID . '&action=edit'));
        exit;
    }
}
add_action('admin_init', 'handle_site_settings_redirects');

function site_settings_admin_css()
{
    // トップページの子要素（インデントさせたいもの）のリスト
    $child_slugs = [
        'site-settings-top-1',
        'site-settings-top-2',
        'site-settings-top-3',
        'site-settings-top-4',
        'site-settings-top-5',
        'site-settings-top-6',
    ];

    echo '<style>';
    foreach ($child_slugs as $slug) {
        echo '#adminmenu a[href="admin.php?page=' . $slug . '"] { padding-left: 2em !important; }';
        echo '#adminmenu li.menu-top a[href="admin.php?page=' . $slug . '"]::before { display: none; }';
    }
    echo '</style>';
}
add_action('admin_head', 'site_settings_admin_css');


///////////////////////////////////////////////////
/////   テーマカラー
///////////////////////////////////////////////////

function my_theme_customize_register($wp_customize)
{
    $wp_customize->add_section('theme_colors', array(
        'title' => 'テーマカラー設定',
        'priority' => 30,
    ));

    $wp_customize->add_setting('color_scheme', array(
        'default' => 'default',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('color_scheme', array(
        'label' => 'カラーバリエーション',
        'section' => 'theme_colors',
        'type' => 'select',
        'choices' => array(
            'default' => 'デフォルト',
            'theme-vivid' => '茶系1',
            'theme-calm' => '茶系2',
            'theme-brown' => '茶系3',
            'dull_terracotta' => '茶系4',
            'sunny-modern-warm' => '茶系5',
            'theme-fresh' => '緑系1',
            'theme-green ' => '緑系2',
            'green_beige' => '緑系3',
            'theme-clear' => '青系',
            'bank_theme' => 'ネイビー系',
            'salmon_pink' => 'サーモンピンク',
            'theme-red' => '赤系',
            'theme-purple' => '紫系',
            'theme-pastel' => 'パステル',
        ),
    ));
}
add_action('customize_register', 'my_theme_customize_register');


function my_theme_body_classes($classes)
{
    // 第2引数に 'default' を指定
    $scheme = get_theme_mod('color_scheme', 'default');

    if ($scheme && $scheme !== 'default') {
        $classes[] = $scheme;
    }
    return $classes;
}
add_filter('body_class', 'my_theme_body_classes');





///////////////////////////////////////////////////
/////   「お知らせ」カスタム投稿タイプ
///////////////////////////////////////////////////

function create_news_post_type()
{
    register_post_type(
        'news', // 投稿タイプ名
        array(
            'labels' => array(
                'name' => __('お知らせ'),
                'singular_name' => __('お知らせ'),
            ),
            'public' => true,
            'has_archive' => true, // 一覧ページを持つ
            'menu_icon' => 'dashicons-megaphone', // メガホンアイコン
            'supports' => array('title', 'editor', 'thumbnail'), // タイトル、本文、アイキャッチ
            'rewrite' => array('slug' => 'news'),
            'show_in_rest' => true, // ブロックエディタ(Gutenberg)を有効化
        )
    );
}
add_action('init', 'create_news_post_type');





///////////////////////////////////////////////////
/////  サイドバーの表示項目カスタマイズ（編集者向け）
///////////////////////////////////////////////////

add_action('admin_menu', function () {
    // 編集者の場合のみ実行
    if (!current_user_can('administrator')) {

        // 1. 不要なメニューを隠す
        remove_menu_page('edit.php?post_type=page'); // 固定ページ一覧
        remove_menu_page('edit-comments.php');       // コメント
        remove_menu_page('plugins.php');            // プラグイン
        remove_menu_page('users.php');              // ユーザー
        remove_menu_page('tools.php');              // ツール
        remove_menu_page('options-general.php');    // 設定

        // 2. 「サイト設定」という名前で、特定の固定ページへの直リンクを作成
        //$settings_page = get_page_by_path('site-settings-page'); // 作成したページのスラッグ
        $settings_page = get_page_by_path('settings'); // 作成したページのスラッグ

        if ($settings_page) {
            add_menu_page(
                'サイト共通設定',                // ページタイトル
                'サイト共通設定',                // メニュー名
                'edit_pages',               // 編集者でもアクセス可能な権限
                'post.php?post=' . $settings_page->ID . '&action=edit', // 編集画面へ直行
                '',                         // コールバック関数不要
                'dashicons-admin-generic',  // アイコン
                25                          // 表示位置
            );
        }
    }
});

// 編集者に必要な権限を付与（一度実行すればOKですが、念のため保持）
add_action('admin_init', function () {
    $role = get_role('editor');
    if ($role && !$role->has_cap('edit_theme_options')) {
        $role->add_cap('edit_theme_options');
    }
});
/**
 * 編集者に対して、編集画面内の「一覧へのリンク」を非表示にする
 */
add_action('admin_head', function () {
    // 管理者以外に適用
    if (!current_user_can('administrator')) {
        echo '<style>
            /* 1. 左上の「固定ページ表示」ボタンやタイトル横のリンクを非表示 */
            .edit-post-header__back,
            .page-title-action,
            .view-switch,
            #view-post-btn,
            /* 2. 管理バー（上部黒帯）の「固定ページを表示」などを非表示 */
            #wp-admin-bar-view,
            /* 3. 投稿一覧へのパンくずリスト等を隠す */
            .wp-header-end + a {
                display: none !important;
            }
        </style>';
    }
});
/**
 * 編集者が固定ページ一覧（page）に直接アクセスしたらダッシュボードへリダイレクト
 * 「お知らせ（post）」やその他の投稿タイプは許可する
 */
add_action('admin_init', function () {
    global $pagenow;

    // 管理者は制限しない
    if (current_user_can('administrator')) return;

    // 現在のページが一覧画面（edit.php）であるかチェック
    if ($pagenow == 'edit.php') {

        // URLのpost_typeパラメータを取得
        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';

        // ★ここを修正：固定ページ(page)の時だけリダイレクトする
        // $post_type == 'post'（お知らせ）の場合は何もしない（一覧が開ける）
        if ($post_type == 'page') {
            wp_redirect(admin_url('index.php')); // ダッシュボードへ
            exit;
        }
    }
});
/**
 * 特定のテキスト（ツールチップ）を書き換える「固定ページ一覧を表示」を「ダッシュボードに戻る」に書き換え
 */
add_filter('gettext', function ($translated_text, $text, $domain) {
    // 管理画面かつ、管理者以外の場合のみ実行
    if (is_admin() && !current_user_can('administrator')) {
        // 元のテキストと一致するかチェック
        if ($text === 'View Pages') { // 内部的な英語テキストを指定
            return 'ダッシュボードに戻る';
        }
    }
    return $translated_text;
}, 20, 3);
