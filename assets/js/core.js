(function () {
	var active = 0;
	var timeoutShow = null;
	var AjaxStatus = {
		show: function () {
			active++;

			if (timeoutShow === null) {
				timeoutShow = window.setTimeout(function () {
					if (active) {
						$('#loadingSpinner').show();
					}

					timeoutShow = null;
				}, 50);
			}
		},
		hide: function () {
			active--;

			if (active === 0) {
				if (timeoutShow !== null) {
					window.clearTimeout(timeoutShow);
					timeoutShow = null;
				}

				$('#loadingSpinner').hide();
			}
		}
	};
	var _darkModeLink = null;

	window.Core = {};
	window.Core.DarkMode = {
		enable: function () {
			if (!_darkModeLink) {
				_darkModeLink = $('<link href="assets/css/bootstrap_slate.min.css" rel="stylesheet">');
				$('head').append(_darkModeLink);
			}
		},
		disable: function () {
			if (_darkModeLink) {
				_darkModeLink.remove();
				_darkModeLink = null;
			}
		},
		toggle: function () {
			if (!_darkModeLink) {
				this.enable();
			}
			else {
				this.disable();
			}

			if (typeof window.localStorage !== 'undefined') {
				window.localStorage.setItem('darkMode', _darkModeLink ? '1' : '0');
			}
		}
	};
	window.Core.AjaxStatus = AjaxStatus;

	if (typeof window.localStorage !== 'undefined') {
		var darkMode = window.localStorage.getItem('darkMode');
		if (darkMode === '1') {
			window.Core.DarkMode.enable();
		}
	}
})();
