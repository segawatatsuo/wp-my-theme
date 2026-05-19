document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('header');
  if (!header) return;

  // ヘッダーのすぐ上に監視用の「点」を作る（HTMLを汚さない自動生成）
  const scrollAnchor = document.createElement('div');
  scrollAnchor.style.position = 'absolute';
  scrollAnchor.style.top = '0';
  document.body.prepend(scrollAnchor);

  const observer = new IntersectionObserver((entries) => {
    // 画面最上部のアンカーが見えなくなったら＝スクロールされたら
    if (!entries[0].isIntersecting) {
      header.classList.add('shadow-md');
      header.classList.replace('border-gray-100', 'border-transparent');
    } else {
      header.classList.remove('shadow-md');
      header.classList.replace('border-transparent', 'border-gray-100');
    }
  }, { threshold: [1.0] });

  observer.observe(scrollAnchor);
});