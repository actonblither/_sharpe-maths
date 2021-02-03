<?php
$menu_sections = $this->_fetch_array_of_menu_sections();
?>
<script>

	var menus = new Array(<?php pv($menu_sections);?>);

	$(document).ready(function(){
		<?php
			// Methods when clicking on the menu icon
			echo $this->_fetch_menu_toggle_jq();
			// Print jq method for clicking the menu item
			echo $this->_fetch_menu_section_click_jq();
			// Print jq method for menu opening on title click
			echo $this->_fetch_menu_section_toggle_jq();
		?>
		});

</script>

<nav id = 'navbar' <?php
rvz($_SESSION['s_sticky_navbar']);
if ($_SESSION['s_sticky_navbar'] == 0){?>class = 'hidden' <?php }?> aria-label = 'Main side menu'>
	<?php
	// fetch home button
	echo $this->_fetch_home_menu_entry();
	//Now each of the sections
	echo $this->_fetch_menu_sections();

?>
</nav>

<script>
<?php
	echo $this->_fetch_menu_visibility_code();
	echo $this->_fetch_menu_section_visibility_code();
?>
</script>
