<?php get_header(); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">「<?php echo esc_html(get_search_query()); ?>」の検索結果</h1>
        <p class="text-sm text-gray-400 mb-8"><?php global $wp_query; echo $wp_query->found_posts; ?> 件見つかりました</p>
        <?php if (have_posts()) : ?>
            <div class="space-y-8">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="border-b pb-8">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-blue-600 transition-colors"><?php the_title(); ?></a>
                        </h2>
                        <p class="text-sm text-gray-400 mb-3"><?php the_date(); ?></p>
                        <p class="text-gray-600 text-sm leading-relaxed"><?php the_excerpt(); ?></p>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="bg-gray-50 rounded-xl p-10 text-center">
                <p class="text-gray-500 mb-6">該当する記事が見つかりませんでした。</p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>
    <div><?php get_sidebar(); ?></div>
</div>
<?php get_footer(); ?>
