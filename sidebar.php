<aside class="space-y-8">
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else : ?>
        <div class="bg-gray-50 rounded-xl p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-3">最近の投稿</h2>
            <ul class="space-y-2 text-sm text-gray-600">
                <?php foreach (wp_get_recent_posts(['numberposts' => 5, 'post_status' => 'publish']) as $p) : ?>
                    <li><a href="<?php echo get_permalink($p['ID']); ?>" class="hover:text-blue-600 transition-colors"><?php echo esc_html($p['post_title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="bg-gray-50 rounded-xl p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-3">カテゴリー</h2>
            <ul class="space-y-2 text-sm text-gray-600">
                <?php wp_list_categories(['show_count' => true, 'title_li' => '']); ?>
            </ul>
        </div>
    <?php endif; ?>
</aside>
