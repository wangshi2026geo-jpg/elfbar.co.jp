(() => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.site-nav');

  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = document.body.classList.toggle('nav-open');
      toggle.setAttribute('aria-expanded', String(open));
    });

    nav.addEventListener('click', (event) => {
      if (event.target.closest('a')) {
        document.body.classList.remove('nav-open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  document.querySelectorAll('.faq-question').forEach((button) => {
    button.addEventListener('click', () => {
      const expanded = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', String(!expanded));
    });
  });

  const year = document.querySelector('[data-year]');
  if (year) year.textContent = new Date().getFullYear();

  document.querySelectorAll('[data-amazon-link]').forEach((link) => {
    link.addEventListener('click', () => {
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        event: 'amazon_outbound_click',
        placement: link.dataset.placement || 'unknown',
        destination: 'amazon_co_jp',
        asin: 'B0HGLTCMB6'
      });
    });
  });

  const statusMessages = {
    check_email: ['is-success', '確認メールを送信しました。メール内のリンクを開くと登録が完了します。'],
    confirmed: ['is-success', 'メール登録が完了しました。今後のお知らせをお待ちください。'],
    unsubscribed: ['is-success', '配信停止を受け付けました。'],
    invalid: ['is-error', '入力内容を確認できませんでした。もう一度お試しください。'],
    expired: ['is-warning', '確認リンクの有効期限が切れています。もう一度登録してください。'],
    not_ready: ['is-warning', '現在、メール登録の受付設定を準備中です。しばらくしてからお試しください。'],
    rate_limited: ['is-warning', '短時間に複数の操作が行われました。時間をおいてお試しください。']
  };

  const params = new URLSearchParams(window.location.search);
  const requestedStatus = params.get('status');
  const referralFromUrl = params.get('ref');

  document.querySelectorAll('[data-newsletter-form]').forEach((form) => {
    const started = form.querySelector('[data-form-started]');
    if (started) started.value = String(Math.floor(Date.now() / 1000));

    const referral = form.querySelector('[name="referral_code"]');
    if (referral && referralFromUrl && /^[A-Za-z0-9-]{4,24}$/.test(referralFromUrl)) {
      referral.value = referralFromUrl.toUpperCase();
    }

    const status = form.querySelector('[data-newsletter-status]');
    if (status && requestedStatus && statusMessages[requestedStatus]) {
      const [type, message] = statusMessages[requestedStatus];
      status.className = `form-status is-visible ${type}`;
      const confirmedCode = params.get('code');
      status.textContent = requestedStatus === 'confirmed' && confirmedCode && /^EBJP-[A-Z0-9_-]{6,12}$/.test(confirmedCode)
        ? `${message} 紹介コード：${confirmedCode}`
        : message;
    }

    form.addEventListener('submit', (event) => {
      if (['127.0.0.1', 'localhost'].includes(window.location.hostname)) {
        event.preventDefault();
        if (status) {
          status.className = 'form-status is-visible is-warning';
          status.textContent = 'これはローカル確認版です。個人情報は送信・保存されません。本番の配信設定確認後に受付を有効化します。';
        }
        return;
      }
      const submit = form.querySelector('button[type="submit"]');
      if (submit) {
        submit.disabled = true;
        submit.textContent = '送信中…';
      }
    });
  });
})();
