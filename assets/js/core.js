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

	window.Core = {};
	window.Core.AjaxStatus = AjaxStatus;
})();
