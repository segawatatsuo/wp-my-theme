<?php get_header(); ?>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6">

        <?php
        // $sections = get_post_meta(get_the_ID(), '_price_sections', true);
        // $sections = $sections ? json_decode($sections, true) : [];

        $sections = get_post_meta($post->ID, '_price_sections', true);
        $sections = is_array($sections) ? $sections : [];
        ?>

        <?php foreach ($sections as $section) : ?>
            <div class="mb-20">

                <!-- 見出し -->
                <h2 class="font-serif text-3xl md:text-4xl font-bold tracking-tight mb-6" style="color: var(--color-text-dark);">
                    <?php echo esc_html($section['title']); ?>
                </h2>

                <!-- 説明文 -->
                <div class="max-w-4xl mb-16">
                    <p class="space-y-6 text-gray-700 leading-loose">
                        <?php echo nl2br(esc_html($section['description'])); ?>
                    </p>
                </div>

                <!-- 料金テーブル -->
                <?php if (!empty($section['rows'])) : ?>
                    <div class="mb-8 overflow-hidden border-t-2 border-primary">
                        <table class="w-full border-collapse border border-gray-200">
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($section['rows'] as $row) : ?>
                                    <tr class="group">
                                        <td class="w-2/3 px-6 py-4 bg-gray-50 text-gray-700 font-medium border-r border-gray-200">
                                            <?php echo esc_html($row['item']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-center bg-white">
                                            <?php echo esc_html($row['price']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- 注意書き -->
                <?php if (!empty($section['warning'])) : ?>
                    <div class="text-sm text-gray-500 leading-relaxed">
                        <!-- <?php echo nl2br(esc_html($section['warning'])); ?> -->
                        <?php echo nl2br(esc_html(str_replace(["\r\n", "\r"], "\n", $section['warning']))); ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div>
</section>

<?php get_footer(); ?>