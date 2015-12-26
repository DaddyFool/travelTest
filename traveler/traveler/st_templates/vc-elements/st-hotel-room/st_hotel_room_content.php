<div class="hotel-room-content">
	<?php
    while(have_posts())
    {
        the_post();
        the_content();
    }
    ?>
</div>