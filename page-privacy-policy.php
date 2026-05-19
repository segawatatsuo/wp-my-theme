<?php get_header(); ?>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6"> <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <header class="mb-12 border-b border-gray-100 pb-8 text-center">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                            <?php the_title(); ?>
                        </h1>
                    </header>

                    <div class="entry-content text-gray-700 text-lg leading-loose space-y-8">
                        <?php the_content(); ?>
                    </div>
                </article>
        <?php endwhile;
                                            endif; ?>

    </div>
</section>

<?php get_footer(); ?>