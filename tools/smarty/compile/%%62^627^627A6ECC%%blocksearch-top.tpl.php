<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blocksearch/blocksearch-top.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlentities', '/home/www/fairplayshop.cz/www/modules/blocksearch/blocksearch-top.tpl', 11, false),array('modifier', 'stripslashes', '/home/www/fairplayshop.cz/www/modules/blocksearch/blocksearch-top.tpl', 11, false),array('function', 'l', '/home/www/fairplayshop.cz/www/modules/blocksearch/blocksearch-top.tpl', 11, false),)), $this); ?>
<!-- Block search module TOP -->
<div id="search_block_top">
	<form method="get" action="<?php echo $this->_tpl_vars['base_dir']; ?>
search.php" id="searchbox">
	<p>
		<label for="search_query"><!-- image on background --></label>
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		
				
		
		<input type="text" id="search_query" name="search_query" value="<?php if (isset ( $_GET['search_query'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$_GET['search_query'])) ? $this->_run_mod_handler('htmlentities', true, $_tmp, $this->_tpl_vars['ENT_QUOTES'], 'utf-8') : htmlentities($_tmp, $this->_tpl_vars['ENT_QUOTES'], 'utf-8')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<?php else: ?><?php echo smartyTranslate(array('s' => 'Vyhledávání','mod' => 'blocksearch'), $this);?>
<?php endif; ?>" onfocus="javascript:if(this.value=='<?php echo smartyTranslate(array('s' => 'Vyhledávání','mod' => 'blocksearch'), $this);?>
')this.value='';" onblur="javascript:if(this.value=='')this.value='<?php echo smartyTranslate(array('s' => 'Vyhledávání','mod' => 'blocksearch'), $this);?>
';" />
		<input type="submit" name="submit_search" value="<?php echo smartyTranslate(array('s' => 'Search','mod' => 'blocksearch'), $this);?>
" class="button" />
		<br class="clear" />
	</p>
	</form>
</div>
<?php if ($this->_tpl_vars['ajaxsearch']): ?>
	<script type="text/javascript">
		<?php echo '
		
		function formatSearch(row) {
			return row[2] + \' > \' + row[1];
		}

		function redirectSearch(event, data, formatted) {
			$(\'#search_query\').val(data[1]);
			document.location.href = data[3];
		}
		
		$(\'document\').ready( function() {
			$("#search_query").autocomplete(
				\''; ?>
<?php echo $this->_tpl_vars['base_dir']; ?>
<?php echo 'search.php\', {
				minChars: 3,
				max:10,
				width:500,
				scroll: false,
				formatItem:formatSearch,
				extraParams:{ajaxSearch:1,id_lang:'; ?>
<?php echo $this->_tpl_vars['cookie']->id_lang; ?>
<?php echo '}
			}).result(redirectSearch)
		});
		'; ?>

	</script>
<?php endif; ?>
<!-- /Block search module TOP -->