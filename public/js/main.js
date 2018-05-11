$(function(){
	var config = {
       basePath: '/path/'
    };

	$(document.body).on('click', '.changeType' ,function(){
		$(this).closest('.cid-input').find('.type-text').text($(this).text());
		$(this).closest('.cid-input').find('.type-input').val($(this).data('type-value'));

	});

	$(document.body).on('click', '.btn-remove-cid' ,function(){
		$(this).closest('.cid-input').remove();
	});

	$(document.body).on('click', '.changeType' ,function(){
		$(this).closest('.proced_prop-input').find('.type-text').text($(this).text());
		$(this).closest('.proced_prop-input').find('.type-input').val($(this).data('type-value'));

	});

	$(document.body).on('click', '.btn-remove-proced_prop' ,function(){
		$(this).closest('.proced_prop-input').remove();
	});

	$(document.body).on('click', '.changeType' ,function(){
		$(this).closest('.anestesia-input').find('.type-text').text($(this).text());
		$(this).closest('.anestesia-input').find('.type-input').val($(this).data('type-value'));
	});
	
	$(document.body).on('click', '.btn-remove-anestesia' ,function(){
		$(this).closest('.anestesia-input').remove();
	});

	$(document.body).on('click', '.changeType' ,function(){
		$(this).closest('.material-input').find('.type-text').text($(this).text());
		$(this).closest('.material-input').find('.type-input').val($(this).data('type-value'));
	});
	
	$(document.body).on('click', '.btn-remove-material' ,function(){
		$(this).closest('.material-input').remove();
	});
});