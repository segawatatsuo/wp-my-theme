<!--footer-->
<footer class="bg-gray-50 text-gray-600 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-12 md:py-16">

        <nav class="mb-12">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer-menu',
                'container'      => false,
                'menu_class'     => 'flex flex-wrap items-center justify-center gap-y-2 text-[13px] font-medium text-gray-500',
                'walker'         => new Footer_Nav_Walker(),
            ));
            ?>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 tracking-tight">
                        <?php
                        $page = get_page_by_path('settings');
                        if ($page) {
                            echo get_field('office_name', $page->ID);
                        }
                        ?>
                    </h2>
                    <p class="text-sm leading-relaxed italic text-gray-400 mb-6">
                        <?php
                        $page = get_page_by_path('settings');
                        if ($page) {
                            echo get_field('office_name_in_english', $page->ID);
                        }
                        ?>
                    </p>
                </div>

                <div class="space-y-2 text-base">
                    <p class="flex items-start gap-2">
                        <span class="font-semibold text-gray-800">住所:</span>
                        <span class="text-lg text-gray-900">
                            <?php
                            $page = get_page_by_path('settings');
                            if ($page) {
                                echo "〒";
                                echo get_field('postal_code', $page->ID);
                                echo "&nbsp;";
                                echo get_field('office_address', $page->ID);
                            }
                            ?>
                        </span>
                    </p>
                    <div class="flex flex-wrap gap-x-8 gap-y-2">
                        <p class="flex items-center gap-2">
                            <span class="font-semibold text-gray-800">Tel:</span>
                            <span class="text-lg text-gray-900">
                                <?php
                                $page = get_page_by_path('settings');
                                if ($page) {
                                    echo get_field('phone_number', $page->ID);
                                }
                                ?>
                            </span>
                        </p>
                        <p class="flex items-center gap-2">
                            <?php
                            $page = get_page_by_path('settings');
                            if ($page) {
                                $fax_number = get_field('fax_number', $page->ID);
                            }
                            ?>
                            <?php if ($fax_number): ?>
                                <span class="font-semibold text-gray-800">Fax:</span>
                                <span class="text-lg text-gray-900"><?php echo $fax_number; ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 max-w-md">
                    <p class="text-sm text-gray-400 leading-relaxed">
                        <?php
                        $page = get_page_by_path('top-footer');
                        if ($page) {
                            $footer_copy = get_field('footer_copy', $page->ID);
                        }
                        echo nl2br(esc_html($footer_copy));
                        ?>
                    </p>
                </div>
            </div>



            <?php
            $page = get_page_by_path('settings');
            if ($page) {
                $address = get_field('office_address', $page->ID);
                $address_encoded = urlencode($address);
            }
            ?>

            <div
                class="w-full h-80 rounded-[2rem] overflow-hidden shadow-sm border border-gray-100 ">
                <?php if ($address): ?>
                    <iframe
                        src="https://www.google.com/maps?q=<?php echo urlencode($address); ?>&output=embed"
                        class="w-full h-full border-0"
                        loading="lazy">
                    </iframe>
                <?php endif; ?>


            </div>
        </div>

        <div class="mt-12 md:mt-16 pt-8 border-t border-gray-200 text-center">
            <p class="text-sm tracking-widest text-gray-400 uppercase">
                <?php
                if ($page) {
                    $copy_rights = get_field('copy_rights', $page->ID);
                }
                ?>
                <?php if ($copy_rights): ?>
                    &copy; <?php echo date('Y'); ?> &nbsp; <?php echo $copy_rights; ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</footer>
<!--footer-->
<?php wp_footer(); ?>
</body>

</html>