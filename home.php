<?php get_header(); ?>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <nav class="text-xs text-gray-400 mb-10 flex gap-2">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary">HOME</a>
            <span>&gt;</span>
            <span class="text-gray-600">
                <?php
                if (is_category()) single_cat_title();
                elseif (is_tag()) single_tag_title();
                else echo 'ブログ一覧';
                ?>
            </span>
        </nav>

        <header class="mb-12">
            <h1 class="font-serif text-3xl font-bold text-gray-900 border-l-4 border-primary pl-4">
                <?php
                if (is_category()) single_cat_title();
                elseif (is_tag()) single_tag_title();
                else echo 'ブログ一覧';
                ?>
            </h1>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article <?php post_class('group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col'); ?>>

                        <a href="<?php the_permalink(); ?>" class="block aspect-video overflow-hidden relative">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']); ?>
                            <?php else : ?>
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300 text-xs">No Image</div>
                            <?php endif; ?>

                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) :
                            ?>
                                <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-primary text-[11px] font-bold px-3 py-1 rounded-full shadow-sm">
                                    <?php echo esc_html($categories[0]->name); ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <div class="p-6 flex flex-col flex-grow">
                            <time class="text-xs text-gray-400 mb-3 block"><?php echo get_the_date('Y.m.d'); ?></time>

                            <h3 class="text-xl font-bold text-gray-900 mb-4 leading-snug group-hover:text-primary transition-colors line-clamp-2">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <div class="text-gray-600 text-sm leading-relaxed mb-6 line-clamp-3">
                                <?php echo wp_trim_words(get_the_excerpt(), 60, '...'); ?>
                            </div>

                            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-end">
                                <a href="<?php the_permalink(); ?>" class="text-primary text-xs font-bold flex items-center gap-1 group-hover:gap-2 transition-all">
                                    詳しく見る
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>

            <?php else : ?>
                <p class="col-span-full text-center text-gray-500 py-20">記事が見つかりませんでした。</p>
            <?php endif; ?>

        </div>

        <div class="mt-16 flex justify-center">
            <?php
            global $wp_query;
            $big = 999999999; // ほとんどありえない数字
            echo paginate_links(array(
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => max(1, get_query_var('paged')),
                'total'     => $wp_query->max_num_pages,
                'type'      => 'list',
                'prev_next' => true,
                'prev_text' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>',
                'next_text' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>',
            ));
            ?>
        </div>

    </div>
</section>

<?php get_footer(); ?>