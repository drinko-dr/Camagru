<?php

?>
<div class="pagination">
		<div class="pag-container">
			<?php for($i = 1; $i <= $gallary['image']['total_page']; $i++) :?>
				<a href="/<?php echo $_GET['option']?>/page/<?php echo $i ?>"><?php echo $i ?></a>
			<?php endfor; ?>
		</div>
	</div>
