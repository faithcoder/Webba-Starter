(function () {
	'use strict';

	var toggle = document.querySelector('.webba-menu-toggle');
	var menu = document.querySelector('#webba-primary-menu');

	if (!toggle || !menu) {
		return;
	}

	toggle.addEventListener('click', function () {
		var isOpen = menu.classList.toggle('is-open');
		toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
	});

	menu.addEventListener('click', function (event) {
		if (event.target && event.target.tagName === 'A') {
			menu.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
		}
	});
}());
