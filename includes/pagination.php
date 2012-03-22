<?php

class Pagination {

	public $items_pp;
	public $total_items;
	public $total_pages;
	public $cur_page;
	public $mid_range;
	public $low;
	public $high;
	public $limit;
	public $return;
	public $default_pp;	

	public $max_pages;
	public $dal;

	public function __construct($params, $default_pp) {
		$this->params = $params;
		$this->dal = new DataAccessLayer();

		$this->default_pp = $default_pp;

		$this->items_pp = (isset($params['items'])) ? $params['items'] : $this->default_pp;

		$this->items = $params['items'];
		$this->phase = $params['phase'];
		$this->p = $params['offset'];
		$this->base = $params['baseurl'];
		$this->page = $params['page'];
		$this->max_pages = 9;
	}

	public function total_items() {
		if (!isset( $this->total_items ) ) {
			$this->total_items = count( $this->dal->gridData(array_merge( $this->params, array( 'items' => 'all' ) ) ) ) ;
		}
		return $this->total_items;
	}

	public function total_pages() {
		if ( !isset( $this->total_pages ) ) {
			$this->total_pages = ceil($this->total_items() / $this->items );
		}
		return $this->total_pages;
	}

	public function render() {
		$total_items = $this->total_items();
		$total_pages = $this->total_pages();

		if ( $this->items == 'all' ) {
			$this->items_pp = $total_items;
			$total_pages = 1;
		} else {
			$total_pages = ceil($total_items / $this->items_pp);
		}

		if ( $this->items != 'all' ) {

        	        print "<ul class='pagerlinks'>";

			if ( $this->p > 0 ) {
				print "<li><a href='" . $this->base . $this->page . "?items=" . $this->items_pp . "&p=" . ($this->p - 1) . "' id='prev'>Prev</a></li>";
			}

			$start = 1;
			$maxpages = 9;
		
			if ( $total_pages > 9 ) {
				$diff = $total_pages - 9;
				$start = max($this->p - 5, 1);
				$maxpages = max($this->p + 5, 9);
				if ( $maxpages > $total_pages ) {
					$maxpages = $total_pages;
					$start = $maxpages - 9;
				}
			}

			$maxpages = min($total_pages, 9);

			$j = 0;
			for ( $i = $start; $i <= $maxpages; $i++ ) {
				if ( $j <= 9 ) { 
        				print "<li><a href='" . $this->base . $this->page . "?items=" . $this->items_pp . "&p=$j'>$i</a></li>";
				}
				$j++;
			} 

			if ( $this->p + 1 < $total_pages ) {
				print "<li><a href='" . $this->base . $this->page . "?items=" . $this->items_pp . "&p=" . ($this->p + 1) . "' id='next'>Next</a></li>";
			}

			print "<li><a href='" . $this->base . $this->page . "?items=" . $total_items . "' id='all'>All</a></li>";
			print "</ul>";
		}
	}
}
