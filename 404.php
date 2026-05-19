<?php get_header(); ?>
<div class="flex flex-col items-center justify-center text-center py-24">
    <p class="text-8xl font-black text-gray-200 mb-4">404</p>
    <h1 class="text-2xl font-bold text-gray-700 mb-3">ページが見つかりません</h1>
    <p class="text-gray-500 mb-8">お探しのページは移動・削除されたか、URLが間違っている可能性があります。</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block bg-blue-600 text-white text-sm font-medium px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
        トップページへ戻る
    </a>
</div>
<?php get_footer(); ?>
