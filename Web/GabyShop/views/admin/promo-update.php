<?
	$error_not_found = FALSE;
	if ( $message )
		if ( $message['type'] == 'error_not_found' )
			$error_not_found = TRUE;
?>

<?
	if ($error_not_found) {
		echo '<p style="color:red">' . $message['value'] . '</p>';
	} else {
?>

<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.datepicker.min.js"></script>

<script type="text/javascript" src="<?= __SITE_CONTEXT ?>views/tiny_mce/tiny_mce.js"></script>

<script>

$(document).ready(function() {
	$('#datepicker').datepicker({
		maxDate: 'Now',
		dateFormat: 'dd/mm/yy'
	});
	
	$('#seo_url').focus();
});

tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
	plugins : "spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
	
	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	
	// Skin options
	skin : "o2k7",
	skin_variant : "silver",
	
	// Example content CSS (should be your site CSS)
	content_css : "css/example.css",
	
	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",
	relative_urls : false,
	//document_base_url : "<?= __SITE_CONTEXT ?>",
	
	// Replace values for the template plugin
	//template_replace_values : {
	  //      username : "Some User",
	    //    staffid : "991234"
	//}
});

var promo_obj = {
	seo_url			: '<?= $promo_backup['seo_url'] ?>',
	subject			: '<?= addslashes( $promo_backup['subject'] ) ?>',
	promo_date		: '<?= $promo_backup['promo_date'] ?>'
};

function my_reset() {
	$('#seo_url').val(promo_obj.seo_url);
	$('#subject').val(promo_obj.subject);
	$('#datepicker').val(promo_obj.promo_date);
	
	tinyMCE.get('promo_content').setContent( $('#promo_content_backup').text() );
}

</script>

<?
	if ( $message ) {
		if ( $message['type'] == 'error' ) {
			echo '<p style="color:red">';
		} else {
			echo '<p style="color:green">';
		}
		echo $message['value'];
		echo '</p>';
	}
?>

<p>
<form action="" method="post">
	<input type="hidden" name="promo_id" value="<?= $promo['id'] ?>" />
	
	<table class="table_no_grid" align="center" width="100%">
		<tbody>
			<tr>
				<td>
					<input type="text" name="seo_url" id="seo_url" size="90" value="<?= $promo['seo_url'] ?>" />
					SEO URL
				</td>
			</tr>
			<tr>
				<td>
					<input type="text" name="subject" id="subject" size="90" value="<?= $promo['subject'] ?>" />
					Chủ đề
				</td>
			</tr>
			<tr>
				<td>
					<input type="text" id="datepicker" name="promo_date" value="<?= $promo['promo_date'] ?>" />
					Ngày tạo [dd/mm/yyyy]
				</td>
			</tr>
			<tr>
				<td>
					<b>Nội dung chương trình</b>
				</td>
			</tr>
			<tr>
				<td>
					<textarea id="promo_content" name="content" style="width:100%;height:500px"><?= $promo['content'] ?></textarea>
				</td>
			</tr>
			
			<tr>
				<td align="center">
					<input id="save-button" style="font-size:20px" type="submit" value="Sửa" />
					<input id="reset-button" style="font-size:20px" type="button" value="Reset" onclick="my_reset()" />
				</td>
			</tr>
			
		</tbody>
	</table>
	<textarea style="display:none" id="promo_content_backup"><?= $promo_backup['content'] ?></textarea>
</form>
</p>

<?
	} // $error_not_found
?>
