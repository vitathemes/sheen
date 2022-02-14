<?php
class Sheen_walker_nav_menu extends Walker_Nav_Menu {
	function start_el( &$output, $item ) {

		$title       = $item->title;
		$permalink   = $item->url;
		$output .= "<li class='" . implode( " ", $item->classes ) . "'>";
	
		//Add SPAN if no Permalink
		if ( $permalink ) {
			$output .= '<a class="menu-item__link u-link--nav h4" href="' . esc_url( $permalink ) . '">';
		}
		$output .= $title;
		if ( $permalink ) {
			$output .= '</a>';
		}
	}
}