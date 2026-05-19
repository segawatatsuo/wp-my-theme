<?php get_header(); ?>
<main class="container mx-auto py-16 px-4 max-w-4xl">



    <?php
    $image = get_field('hero_image'); // フィールド名を指定

    if (!empty($image)): ?>
        <img src="<?php echo esc_url($image['url']); ?>"
            alt="<?php echo esc_attr($image['alt']); ?>" />

        <?php
        // 特定のサイズ（例: medium）で出したい場合
        // $image['sizes']['medium'] 
        ?>
    <?php endif; ?>


    <?php
    // グループ内のすべてのフィールド設定を取得
    $fields = acf_get_fields('group_69da50adbb6ac');

    if ($fields) {
        foreach ($fields as $field) {
            // フィールド名が一致するかチェック
            if ($field['name'] === 'copy_in_english') {
                $value = get_field($field['name']);

                // ラベル（メインビジュアル等）と値を表示
                echo '<h3>' . esc_html($field['label']) . '</h3>';
                echo '<p>' . esc_html($value) . '</p>';
            }
        }
    }

    if ($fields) {
        foreach ($fields as $field) {
            // フィールド名が一致するかチェック
            if ($field['name'] === 'main_copy') {
                $value = get_field($field['name']);

                // ラベル（メインビジュアル等）と値を表示
                echo '<h3>' . esc_html($field['label']) . '</h3>';
                echo '<p>' . esc_html($value) . '</p>';
            }
        }
    }
    if ($fields) {
        foreach ($fields as $field) {
            // フィールド名が一致するかチェック
            if ($field['name'] === 'copy') {
                $value = get_field($field['name']);

                // ラベル（メインビジュアル等）と値を表示
                echo '<h3>' . esc_html($field['label']) . '</h3>';
                echo '<p>' . esc_html($value) . '</p>';
            }
        }
    }
    if ($fields) {
        foreach ($fields as $field) {
            // フィールド名が一致するかチェック
            if ($field['name'] === 'button_text') {
                $value = get_field($field['name']);

                // ラベル（メインビジュアル等）と値を表示
                echo '<h3>' . esc_html($field['label']) . '</h3>';
                echo '<p>' . esc_html($value) . '</p>';
            }
        }
    }
    ?>
</main>
<?php get_footer(); ?>