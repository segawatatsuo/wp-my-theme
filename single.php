<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6">

                <nav class="text-xs text-gray-400 mb-10 flex gap-2">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary">HOME</a>
                    <span>&gt;</span>

                    <?php
                    // 現在の投稿タイプを取得
                    $post_type = get_post_type();

                    if ($post_type === 'news') :
                        // お知らせ（news）の場合
                    ?>
                        <a href="<?php echo get_post_type_archive_link('news'); ?>" class="hover:text-primary">お知らせ一覧</a>
                    <?php else :
                        // それ以外（通常のブログ）の場合
                    ?>
                        <a href="<?php echo get_post_type_archive_link('post'); ?>" class="hover:text-primary">ブログ一覧</a>
                    <?php endif; ?>

                    <span>&gt;</span>
                    <span class="text-gray-600 truncate"><?php the_title(); ?></span>
                </nav>



                <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">

                    <main class="lg:w-2/3">
                        <article <?php post_class(); ?>>
                            <header class="mb-10">
                                <div class="flex items-center gap-4 mb-4">
                                    <time class="text-sm text-gray-500 font-medium" datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date('Y.m.d'); ?>
                                    </time>
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) :
                                    ?>
                                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="bg-primary/10 text-primary text-xs font-bold px-3 py-1 rounded-full hover:bg-primary/20 transition-colors">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <h1 class="font-serif text-3xl md:text-4xl font-bold leading-tight mb-8" style="color: var(--color-text-dark);">
                                    <?php the_title(); ?>
                                </h1>

                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
                                        <?php the_post_thumbnail('large', ['class' => 'w-full h-auto object-cover']); ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <div class="entry-content text-gray-700 text-lg leading-loose space-y-8">
                                <?php the_content(); ?>
                            </div>

                            <footer class="mt-16 pt-8 border-t border-gray-100">
                                <div class="flex flex-col sm:flex-row justify-between items-center gap-6">
                                    <div class="flex gap-4">
                                        <span class="text-sm font-bold text-gray-400 self-center">SHARE</span>
                                        <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank" rel="nofollow" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-primary hover:text-white transition-colors">𝕏</a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" rel="nofollow" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-primary hover:text-white transition-colors">f</a>
                                    </div>
                                    <a href="<?php echo get_post_type_archive_link('post'); ?>" class="text-sm font-bold text-primary hover:underline">一覧に戻る</a>
                                </div>
                            </footer>
                        </article>
                    </main>

                    <aside class="lg:w-1/3 space-y-12">

                        <div>
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-200 pb-2">カテゴリー</h3>
                            <ul class="space-y-2">
                                <?php
                                $cats = get_categories();
                                foreach ($cats as $cat) :
                                ?>
                                    <li>
                                        <a href="<?php echo get_category_link($cat->term_id); ?>" class="flex justify-between items-center py-2 px-4 rounded-lg hover:bg-gray-50 text-gray-700 transition-colors">
                                            <span class="text-sm font-medium"><?php echo $cat->name; ?></span>
                                            <span class="text-xs text-gray-400 bg-white px-2 py-0.5 rounded border border-gray-100 shadow-xs"><?php echo $cat->count; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-200 pb-2">最近の投稿</h3>
                            <div class="space-y-6">
                                <?php
                                $recent_posts = get_posts(['posts_per_page' => 5, 'post_status' => 'publish']);
                                foreach ($recent_posts as $post) : setup_postdata($post);
                                ?>
                                    <a href="<?php the_permalink(); ?>" class="group flex gap-4">
                                        <div class="w-20 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('thumbnail', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform']); ?>
                                            <?php else : ?>
                                                <div class="w-full h-full flex items-center justify-center text-[10px] text-gray-300">No Image</div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-800 leading-snug group-hover:text-primary transition-colors line-clamp-2">
                                                <?php the_title(); ?>
                                            </h4>
                                            <time class="text-[10px] text-gray-400"><?php echo get_the_date('Y.m.d'); ?></time>
                                        </div>
                                    </a>
                                <?php endforeach;
                                wp_reset_postdata(); ?>
                            </div>
                        </div>

                    </aside>
                </div>
            </div>
        </section>
<?php endwhile;
endif; ?>
<?php get_footer(); ?>