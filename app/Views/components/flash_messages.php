<?php
use App\Core\Session;

$flashes = Session::getFlashes();
if (!empty($flashes)):
    foreach ($flashes as $type => $messages):
        $alertClass = match($type) {
            'success' => 'alert-success',
            'error' => 'alert-error',
            'warning' => 'alert-warning',
            default => 'alert-info'
        };
        foreach ($messages as $msg):
?>
    <div class="alert <?= $alertClass ?> role="alert"">
        <span><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></span>
        <button type="button" onclick="this.parentElement.remove()" style="background:transparent; border:none; cursor:pointer; font-size:1.1rem; line-height:1; color:inherit;">&times;</button>
    </div>
<?php 
        endforeach;
    endforeach;
endif;
?>
