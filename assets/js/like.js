$(document).ready(function () {
	$(document).on('click', '.jsVote', function () {
		var likeType = $(this).attr('data-type');
		var jsObject = $(this).closest('.jsObject');
		var objectID = jsObject.attr('data-id');
		var objectType = jsObject.attr('data-type');

		window.Core.AjaxStatus.show();
		$.post('?ajax', {
			className: '\\data\\like\\LikeAction',
			actionName: 'like',
			parameters: {
				likeType,
				objectID,
				objectType
			}
		}, function (data) {
			var objectContainer = $('.jsObject[data-type="' + objectType + '"][data-id="' + objectID + '"]');
			for (var key in data.returnValues) {
				if (data.returnValues.hasOwnProperty(key)) {
					key = parseInt(key);
					var button;
					if (key === -1) {
						button = objectContainer.find('[data-type="dislike"]');
					}
					else if (key === 1) {
						button = objectContainer.find('[data-type="like"]');
					}
					else {
						continue;
					}

					var currentCount = parseInt(button.attr('data-count')) + data.returnValues[key];
					button.attr('data-count', currentCount);
					button.find('.likeValue').html(currentCount);
					if (button.hasClass('btn')) {
						button.removeClass('btn-danger btn-success btn-default');
						if (data.returnValues[key] === 1) {
							if (key === -1) {
								button.addClass('btn-danger');
							}
							else {
								button.addClass('btn-success');
							}
						}
						else {
							button.addClass('btn-default');
						}
					}
				}
			}

			Core.AjaxStatus.hide();
		}).fail(function (jqXHR) {
			try {
				alert('Error while saving: ' + JSON.parse(jqXHR.responseText).message);
			}
			catch (e) {
				alert('Unknown error while saving...');
			}

			Core.AjaxStatus.hide();
		});
	});
});