<?php
// Shared footer scripts and closing tags.
?>
<!-- js -->
<script src="<?php echo asset('vendors/scripts/core.js'); ?>"></script>
<script src="<?php echo asset('vendors/scripts/script.min.js'); ?>"></script>
<script src="<?php echo asset('vendors/scripts/process.js'); ?>"></script>
<script src="<?php echo asset('vendors/scripts/layout-settings.js'); ?>"></script>

<?php foreach ($pageScripts as $scriptPath): ?>
<script src="<?php echo asset($scriptPath); ?>"></script>
<?php endforeach; ?>

<!-- Google Tag Manager (noscript) -->
<noscript>
	<iframe
		src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
		height="0"
		width="0"
		style="display: none; visibility: hidden"
	></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
</body>
</html>