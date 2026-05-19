<?php get_header(); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2">
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <h1 class="text-3xl font-bold text-gray-900 mb-8"><?php the_title(); ?></h1>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-8 rounded-xl overflow-hidden">
                        <?php the_post_thumbnail('large', ['class' => 'w-full h-auto object-cover']); ?>
                    </div>
                <?php endif; ?>
                <div class="text-gray-700 leading-relaxed space-y-4">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>

</div>
<?php get_footer(); ?>