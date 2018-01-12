$(function(){
	var config = {
       basePath: '/path/'
    };
	/*$(document.body).on('click', '.changeType' ,function(){
		$(this).closest('.phone-input').find('.type-text').text($(this).text());
		$(this).closest('.phone-input').find('.type-input').val($(this).data('type-value'));
	});

	$(document.body).on('click', '.btn-remove-phone' ,function(){
		$(this).closest('.phone-input').remove();
	});


	$('.btn-add-phone').click(function(){

		var index = $('.phone-input').length + 1;

		$('.phone-list').append(''+
			'<div class="input-group phone-input">'+
			'<input type="text" name="telefone['+index+']" id="telefone['+index+']" class="form-control" placeholder="(99) 99999 9999" />'+
			'<span class="input-group-btn">'+
			'<button class="btn btn-danger btn-remove-phone" type="button"><span class="glyphicon glyphicon-minus"></span></button>'+
			'</span>'+
			'</div>'
			);

	}); */

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


	
});