<?php
// args:
// $level : flash level
// $message : flash message
if (!isset($level)) $level="info";
 ?>
<div class="flash-container-<?=$level?>">
  <p><?=htmlspecialchars($message)?></p>
</div>
