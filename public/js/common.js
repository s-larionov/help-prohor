$(function() {
	$('form#qiwi').submit(function() {
		var self = $(this), data = self.serialize();
		$.post(this.action, data)
			.success(function(data) {
				console.log(data);
				if (data.status == 'success') {
					location.href = self.data('qiwi') || 'http://qiwi.ru';
				} else {
					self.find('input[name=' + i + ']').removeClass();
					$.each(data.errors || [], function(i) {
						self.find('input[name=' + i + ']').addClass('error');
					});
				}
			})
			.error(function() {
				alert('Неизвестная ошибка. Попробуйте позже.');
			});


		return false;
	});
});
