<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blocktopmenu/blocktopmenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/blocktopmenu/blocktopmenu.tpl', 13, false),)), $this); ?>
        <?php if ($this->_tpl_vars['MENU'] != ''): ?>
        </div>
				<!-- Menu -->
        <div class="sf-contener">
        <div class="bgright">
          <ul class="sf-menu">
            <?php echo $this->_tpl_vars['MENU']; ?>

            <?php if ($this->_tpl_vars['MENU_SEARCH']): ?>
            <li class="sf-search noBack" style="float:right">
              <form id="searchbox" action="search.php" method="get">
                <input type="hidden" value="position" name="orderby"/>
                <input type="hidden" value="desc" name="orderway"/>
                <input type="text" name="search_query" value="<?php if (isset ( $_GET['search_query'] )): ?><?php echo $_GET['search_query']; ?>
<?php else: ?><?php echo smartyTranslate(array('s' => 'Enter a product name'), $this);?>
<?php endif; ?>" onfocus="javascript:if(this.value=='<?php echo smartyTranslate(array('s' => 'Enter a product name'), $this);?>
')this.value='';" onblur="javascript:if(this.value=='')this.value='<?php echo smartyTranslate(array('s' => 'Enter a product name'), $this);?>
';" />
              </form>
            </li>
            <?php endif; ?>
          </ul>
        </div>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['this_path']; ?>
js/hoverIntent.js"></script>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['this_path']; ?>
js/superfish-modified.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['this_path']; ?>
css/superfish-modified.css" media="screen">
				<!--/ Menu -->
        <?php endif; ?>	