<?php get_header(); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">最新の投稿</h1>

        <?php if (have_posts()) : ?>
            <div class="space-y-8">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="border-b pb-8">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="block mb-4 rounded-xl overflow-hidden">
                                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-48 object-cover hover:opacity-90 transition-opacity']); ?>
                            </a>
                        <?php endif; ?>
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-blue-600 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <p class="text-sm text-gray-400 mb-3"><?php the_date(); ?> / <?php the_category(', '); ?></p>
                        <p class="text-gray-600 text-sm leading-relaxed"><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="inline-block mt-4 text-sm text-blue-600 hover:underline">続きを読む →</a>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="mt-10 flex justify-between text-sm">
                <?php
                $prev = get_previous_posts_link('← 新しい記事');
                $next = get_next_posts_link('古い記事 →');
                if ($prev) echo "<span class='text-blue-600 hover:underline'>$prev</span>";
                if ($next) echo "<span class='text-blue-600 hover:underline ml-auto'>$next</span>";
                ?>
            </div>
        <?php else : ?>
            <p class="text-gray-500">投稿がありません。</p>
        <?php endif; ?>
    </div>
    <div><?php get_sidebar(); ?></div>
</div>
<?php get_footer(); ?>