function getListFiles() {
	var str = '';
	$('#media-items').find('div[hehe="0"]').each(function(index) {
		if (str == '') {
			str += $(this).text();
		} else {
			str += '/' + $(this).text();
		}
	});
	return str;
}

function getLength() {
	return $('#media-items').find('div[hehe="0"]').length;
}

function showItemsList(pics) {
	var pic_arr = pics.split('/');
	for (var i = 0; i < pic_arr.length; i++) {
		$('#media-items').append(
			makeItemHtml_After( 'my_' + i, pic_arr[i] )
		);
	}
}

function makeItemHtml(id, name) {
	var html =
	'<div id="media-item-' + id + '" style="cursor:move;border:2px solid #A5ACB2;margin-top:10px;background-color:#FFFFFF">' +
		'<div id="media-header-' + id + '" style="border-bottom:1px solid #A5ACB2">' +
			'<table width="100%">' +
				'<tr>' +
					'<td><div hehe="0" style="font-weight:bold;width:300px;overflow:hidden" id="txt_' + id + '">' + name + '</div></td>' +
					'<td align="right">' +
						'<input style="display:none" type="button" value="Loại bỏ" onclick="removeMedia(\'' + id + '\')" />' +
						'<span id="span_loading_' + id + '">Upload...</span>' +
					'</td>' +
				'</tr>' +
			'</table>' +
		'</div>' +
		'<div id="media-prog-' + id + '" class="progbar" style="width:0;height:10px">' +
		'</div>' +
	'</div>';
	return html;
}

function makeItemHtml_After(id, name) {
	var html =
	'<div id="media-item-' + id + '" style="cursor:move;border:2px solid #A5ACB2;margin-top:10px;background-color:#FFFFFF">' +
		'<div>' +
			'<table width="100%">' +
				'<tr>' +
					'<td><div hehe="0" style="font-weight:bold;width:300px;overflow:hidden" id="txt_' + id + '">' + name + '</div></td>' +
					'<td align="right">' +
						'<input type="button" value="Loại bỏ" onclick="removeMedia(\'' + id + '\')" />' +
					'</td>' +
				'</tr>' +
			'</table>' +
		'</div>' +
	'</div>';
	return html;
}

function fileQueued(fileObj) {
	$('#media-items').append( makeItemHtml(fileObj.id, fileObj.name) );
	$('#save-button').attr('disabled', 'disabled');
	$('#reset-button').attr('disabled', 'disabled');
}

function removeMedia(id) {
	$('#media-item-' + id).remove();
}

function uploadProgress(fileObj, bytesDone, bytesTotal) {
	$('#media-prog-' + fileObj.id).width(100 *  bytesDone / bytesTotal + '%');
}

function uploadSuccess(fileObj, serverData) {
	$('#media-prog-' + fileObj.id).remove();
	$('#media-header-' + fileObj.id).find('input[type="button"]').css('display', 'inline');
	$('#media-header-' + fileObj.id).css('border-bottom', '0');
	$('#span_loading_' + fileObj.id).remove();
	
	$('#txt_' + fileObj.id).text(serverData);
	
	if ( swfu.getStats().files_queued == 0 ) {
		$('#save-button').attr('disabled', '');
		$('#reset-button').attr('disabled', '');
	} else {
		this.startUpload();
	}
}

function fileDialogComplete(num_files_queued) {
	if (num_files_queued > 0) {
		this.startUpload();
	}
}
