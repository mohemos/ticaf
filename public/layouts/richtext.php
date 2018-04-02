<script type="text/javascript" src="<?php echo layout_path("js/richtext.js"); ?>"></script>
		 <!--script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script-->
		 <script type="text/javascript">
		//<![CDATA[
		  bkLib.onDomLoaded(function() {
				new nicEditor({buttonList : ['fontSize','style','fontFamily','fontFormat','bold','italic','underline','strikeThrough','subscript','superscript','html','image','link','unlink','left','right','center','justify','indent','outdent','ol','ul','removeformat']}).panelInstance('richtext');
		  });
		  //]]>
		  </script>