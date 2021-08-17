<?php

if (!defined('_PS_VERSION_'))
	exit();
ob_flush();
?>
				</div>
			</div>
			<p id="footer">								
				<?php echo number_format(microtime(true) - $timerStart, 3, '.', ''); ?>s
			</p>
		</div>
	</body>
</html>
<?php
	//ob_end_flush();
?>