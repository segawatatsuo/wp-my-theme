<?php get_header(); ?>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6">

        <div class="text-center mb-16">
            <h2 class="font-serif text-3xl md:text-4xl font-bold tracking-tight" style="color: var(--color-text-dark);">よくある質問</h2>
        </div>

        <?php
        $faqs = get_post_meta(get_the_ID(), '_faqs', true);
        $faqs = $faqs ? json_decode($faqs, true) : [];

        // カテゴリーごとにグループ化
        $grouped = [];
        foreach ($faqs as $faq) {
            $category = !empty($faq['category']) ? $faq['category'] : 'その他';
            $grouped[$category][] = $faq;
        }
        ?>

        <?php if (!empty($grouped)) : ?>
            <?php foreach ($grouped as $category => $items) : ?>

                <div class="mb-16">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1 h-6 bg-primary"></div>
                        <h3 class="text-xl font-bold text-gray-900"><?php echo esc_html($category); ?></h3>
                    </div>

                    <div class="space-y-4">
                        <?php foreach ($items as $item) : ?>
                            <details class="group border border-gray-200 rounded-2xl overflow-hidden shadow-sm transition-all duration-300 open:shadow-md">
                                <summary class="flex items-center justify-between p-6 cursor-pointer bg-white hover:bg-gray-50 list-none">
                                    <div class="flex items-center gap-4">
                                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary font-black flex items-center justify-center text-sm">Q</span>
                                        <span class="font-serif font-bold text-gray-800 leading-relaxed">
                                            <?php echo esc_html($item['question']); ?>

                                        </span>
                                    </div>
                                    <span class="text-gray-400 group-open:rotate-180 transition-transform duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </summary>
                                <div class="px-6 pb-8 pt-6 bg-white border-t border-gray-50 flex gap-4">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-info-600/10 text-info-600 font-black flex items-center justify-center text-sm mt-1">A</span>
                                    <div class="text-gray-700 leading-loose text-sm md:text-base">
                                        <?php echo wpautop(esc_html($item['answer'])); ?>
                                    </div>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>