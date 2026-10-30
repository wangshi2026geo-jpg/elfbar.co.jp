(function () {
  const button = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.nav');
  if (!button || !nav) return;
  button.addEventListener('click', function () {
    const open = nav.classList.toggle('is-open');
    button.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
})();

