const navToggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');
navToggle?.addEventListener('click', () => {
  navLinks?.classList.toggle('open');
  navToggle.setAttribute('aria-expanded', navLinks?.classList.contains('open') ? 'true' : 'false');
});

document.querySelectorAll('.faq-q').forEach(btn => btn.addEventListener('click', () => {
  btn.closest('.faq-item')?.classList.toggle('open');
}));

document.querySelectorAll('[data-tab]').forEach(btn => btn.addEventListener('click', () => {
  const id = btn.dataset.tab;
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById(id)?.classList.add('active');
}));

// Contact forms: create a pre-filled email so the static site can receive inquiries immediately.
document.querySelectorAll('form[data-mailto]').forEach(form => {
  form.addEventListener('submit', event => {
    event.preventDefault();
    const data = new FormData(form);
    const lines = [];
    for (const [key, value] of data.entries()) {
      const label = form.querySelector(`[name="${CSS.escape(key)}"]`)?.closest('.field')?.querySelector('label')?.textContent?.trim() || key;
      lines.push(`${label}: ${value}`);
    }
    const isPartner = form.id === 'partner';
    const subject = isPartner ? 'ELFBAR Japan 卸売相談' : 'ELFBAR Japan お問い合わせ';
    const body = ['ELFBAR Japan ご担当者様', '', '以下の内容で問い合わせます。', '', ...lines, '', '送信元ページ: ' + location.href].join('\n');
    location.href = `mailto:${form.dataset.mailto}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
  });
});
