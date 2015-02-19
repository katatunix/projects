var g_confirmDialog = null;

function initConfirmDialog(id) {
	g_confirmDialog = $('#' + id);
	g_confirmDialog.contentElement = g_confirmDialog.find('p');

	g_confirmDialog.onYes = null;
	g_confirmDialog.onNo = null;

	g_confirmDialog.dialog({
		autoOpen: false,
		width: 330,
		modal: true,
		buttons: {
			'Yes' : function() {
				if (g_confirmDialog.onYes) g_confirmDialog.onYes(g_confirmDialog.data);
				g_confirmDialog.dialog('close');
			},
			'No' : function() {
				if (g_confirmDialog.onNo) g_confirmDialog.onNo(g_confirmDialog.data);
				g_confirmDialog.dialog('close');
			}
		},
		close: function() {
			if (g_confirmDialog.onNo) g_confirmDialog.onNo(g_confirmDialog.data);
		}
	});
}

function showConfirmDialog(title, content, onYes, onNo, data) {
	g_confirmDialog.onYes = onYes;
	g_confirmDialog.onNo = onNo;
	
	g_confirmDialog.data = data;
	
	g_confirmDialog.dialog('option', 'title', title);
	
	g_confirmDialog.contentElement.text(content);
	g_confirmDialog.dialog('open');
}

//==========================================================================================================
//==========================================================================================================
var g_infoDialog = null;

function initInfoDialog(id) {
	g_infoDialog = $('#' + id);
	g_infoDialog.contentElement = g_infoDialog.find('p');

	g_infoDialog.dialog({
		autoOpen	: false,
		width		: 330,
		modal		: true,
		buttons		: {
			'OK'		: function() {
				g_infoDialog.dialog('close');
			}
		}
	});
}

function showInfoDialog(title, content, isError) {
	g_infoDialog.dialog('option', 'title', title);
	
	g_infoDialog.contentElement.css('color', isError ? 'red' : 'black');
	g_infoDialog.contentElement.text(content);
	
	g_infoDialog.dialog('open');
}

//==========================================================================================================
//==========================================================================================================
var g_progressDialog = null;

function initProgressDialog(id) {
	g_progressDialog = $('#' + id);

	g_progressDialog.dialog({
		autoOpen	: false,
		width		: 330,
		modal		: true
	});
}

function showProgressDialog(title, allowCancel, onCancel) {
	g_progressDialog.dialog('option', 'title', title);
	g_progressDialog.onCancel = onCancel;
	
	if (allowCancel) {
		g_progressDialog.dialog('option', 'buttons', {
			'Cancel'	: function() {
				if (g_progressDialog.onCancel) g_progressDialog.onCancel();
				g_progressDialog.dialog('close');
			}
		});
		g_progressDialog.dialog('option', 'height', 'auto');
	} else {
		g_progressDialog.dialog('option', 'buttons', {
		});
		g_progressDialog.dialog('option', 'height', 95);
	}
	
	g_progressDialog.dialog('open');
}

function closeProgressDialog() {
	g_progressDialog.dialog('close');
}
