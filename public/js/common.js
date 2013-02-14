$(function() {
	$('form#qiwi').submit(function() {
		var self = $(this), data = self.serialize();
		self.find('*[type=submit]').attr('disabled', 'disabled');
		$.post(this.action, data)
			.success(function(data) {
				self.find('input').removeClass();
				if (data.status == 'success') {
					location.href = self.data('qiwi') || 'http://qiwi.ru';
				} else {
					$.each(data.errors || [], function(i) {
						self.find('input[name=' + i + ']').addClass('error');
					});
				}
				self.find('*[type=submit]').removeAttr('disabled');
			})
			.error(function() {
				alert('Неизвестная ошибка. Попробуйте позже.');
			});


		return false;
	});
});
