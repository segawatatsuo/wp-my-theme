<?php get_header(); ?>

<div class="representative-wrap">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <section class="py-16 bg-white">
                <div class="max-w-4xl mx-auto px-6">
                    <div class="flex flex-col md:flex-row items-start gap-10">

                        <!-- 写真 -->
                        <div class="w-40 md:w-48 flex-shrink-0">
                            <div class="aspect-[3/4] overflow-hidden rounded-lg border border-gray-200">
                                <?php $photo = get_field('representative_photo'); ?>
                                <?php if ($photo) : ?>
                                    <img src="<?php echo esc_url($photo['url']); ?>"
                                        alt="<?php echo esc_attr($photo['alt']); ?>"
                                        class="w-full h-full object-cover grayscale-[20%]">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex-1">

                            <!-- 資格名称 -->
                            <p class="text-sm text-gray-500 mb-1"><?php the_field('representative_qualification_name'); ?></p>

                            <!-- お名前 -->
                            <h1 class="font-serif text-3xl font-bold text-gray-900 mb-2">
                                <?php the_field('representative_name'); ?>
                            </h1>

                            <!-- 会社名 -->
                            <p class="font-serif text-lg font-bold text-primary mb-4"><?php the_field('representative_company'); ?></p>

                            <!-- 所有資格 -->
                            <p class="text-sm text-gray-600 border-b border-gray-100 pb-6 mb-6">
                                保有資格：<?php the_field('representative_ownership_qualification'); ?>
                            </p>

                            <!-- 挨拶文 -->
                            <div class="space-y-6 text-gray-700 leading-loose">

                                <?php echo nl2br(get_field('representative_greeting_message')); ?>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <!-- 経歴 -->
            <section class="py-16 max-w-7xl mx-auto border-y border-gray-100">
                <div class="max-w-4xl mx-auto px-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                        <span class="w-1 h-5 bg-gray-400"></span>
                        経歴
                    </h2>

                    <?php
                    $histories = get_post_meta(get_the_ID(), '_histories', true);
                    $histories = $histories ? json_decode($histories, true) : [];

                    if (!empty($histories)) : ?>
                        <dl class="space-y-4 text-sm md:text-base">
                            <?php foreach ($histories as $index => $item) :
                                $is_last = $index === array_key_last($histories);
                            ?>
                                <div class="flex flex-col sm:flex-row sm:gap-8 <?php echo $is_last ? 'pb-4' : 'border-b border-gray-100 pb-4'; ?>">
                                    <dt class="sm:w-32 font-normal text-gray-600 mb-1 sm:mb-0">
                                        <?php echo esc_html($item['year']); ?>
                                    </dt>
                                    <dd class="text-gray-700">
                                        <?php echo esc_html($item['content']); ?>
                                    </dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    <?php endif; ?>

                </div>
            </section>

    <?php endwhile;
    endif; ?>

</div>

<?php get_footer(); ?>