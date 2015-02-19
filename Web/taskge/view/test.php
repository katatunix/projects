<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.mouse.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.button.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.draggable.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.autocomplete.min.js"></script>



<script type="text/javascript">
	$(document).ready(function() {
		
		$( "#autocomplete" ).autocomplete({
  			source: [	"Fix bugs on QADB•Bug id: 12345, 23456, 78901.",
						"Java•Various Germanic tribes occupied what is now northern Germany and southern Scandinavia since classical antiquity. A region named Germania was documented by the Romans before AD 100. During the Migration Period that coincided with the decline of the Roman Empire, the Germanic tribes expanded southward and established kingdoms throughout much of Europe. Beginning in the 10th century, German territories formed a central part of the Holy Roman Empire.",
						"PHP•Various Germanic tribes occupied what is now northern Germany and southern Scandinavia since classical antiquity. A region named Germania was documented by the Romans before AD 100. During the Migration Period that coincided with the decline of the Roman Empire, the Germanic tribes expanded southward and established kingdoms throughout much of Europe. Beginning in the 10th century, German territories formed a central part of the Holy Roman Empire.",
						"Coldfusion•Various Germanic tribes occupied what is now northern Germany and southern Scandinavia since classical antiquity. A region named Germania was documented by the Romans before AD 100. During the Migration Period that coincided with the decline of the Roman Empire, the Germanic tribes expanded southward and established kingdoms throughout much of Europe. Beginning in the 10th century, German territories formed a central part of the Holy Roman Empire.",
						"Javascript•Various Germanic tribes occupied what is now northern Germany and southern Scandinavia since classical antiquity. A region named Germania was documented by the Romans before AD 100. During the Migration Period that coincided with the decline of the Roman Empire, the Germanic tribes expanded southward and established kingdoms throughout much of Europe. Beginning in the 10th century, German territories formed a central part of the Holy Roman Empire.",
						"ASP.NET•Various Germanic tribes occupied what is now northern Germany and southern Scandinavia since classical antiquity. A region named Germania was documented by the Romans before AD 100. During the Migration Period that coincided with the decline of the Roman Empire, the Germanic tribes expanded southward and established kingdoms throughout much of Europe. Beginning in the 10th century, German territories formed a central part of the Holy Roman Empire.",
						"Ruby•Various Germanic tribes occupied what is now northern Germany and southern Scandinavia since classical antiquity. A region named Germania was documented by the Romans before AD 100. During the Migration Period that coincided with the decline of the Roman Empire, the Germanic tribes expanded southward and established kingdoms throughout much of Europe. Beginning in the 10th century, German territories formed a central part of the Holy Roman Empire." ],
			minLength: 0,
			open: function() {
				$('#autocomplete').autocomplete("widget").width($('#autocomplete').width())
			},
			select: function( event, ui ) {
				$('#xxx').val(ui.item.label);
			}
		});
		
		$( "#autocomplete" ).click(function() {
			$( "#autocomplete" ).autocomplete( "search" );
		});
		
		$.ui.autocomplete.prototype._renderItem = function(ul, item) {
			return $( "<li>" )
			    .data("item.autocomplete", { value: item.label.split('•')[0], label: item.label.split('•')[1] })
			    .append( $( "<a>" )
				.text( item.label.split('•')[0] )
				.append( $( "<div>" ).css('font-size', '11px').text( item.label.split('•')[1] ) ) )
			    .appendTo( ul );
				
		}
		
		$.ui.autocomplete.prototype._resizeMenu = function() {
		    var ul = this.menu.element;
			
			ul.css('max-height', '500px');
			ul.css('overflow-y', 'auto');
		}
	});
	
</script>



<div id="content">
	<div id="content2">
		<div class="post">
			<h2 class="title"><?= $title?></h2>
			
			<div class="entry">
			
<label for="autocomplete">Select a programming language: </label>
<input id="autocomplete" style="width:500px" />

<textarea id="xxx"></textarea>

			</div>
		</div>
		
		<? include __VIEW_DIR_PATH . '/tiles/about.php'; ?>
	</div>
</div>
<!-- end #content -->

<div id="sidebar">
	<ul>
		<? include __VIEW_DIR_PATH . '/tiles/gameloftlogo.php'; ?>
	</ul>
</div>
<!-- end #sidebar -->
