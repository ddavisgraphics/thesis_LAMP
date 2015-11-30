<?php
    // Add Header
    templates::display('header');
?>

<section class="main">
    <div class="container">
        <?php require_once('includes/routes.php'); ?>
    </div>
</section>

<?php
    // Add Footer
    templates::display('footer');
?>