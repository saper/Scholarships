<?php

class Pagination {

	protected $total_items;
	protected $total_pages;
	protected $dal;

	public function __construct($params) {
		$this->params = $params;
		$this->dal = new DataAccessLayer();
		$this->items = $params['items'];
		$this->phase = $params['phase'];
		$this->p = $params['offset'];
		$this->base = $params['baseurl'];
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

		print "<ul class='pagerlinks'>";
		print "<li><a href='" . $this->base . "review/phase" . $this->phase . "?items=" . $this->items . "&p=" . ($this->p - 1) . "' id='prev'>Prev</a></li>";

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

		for ( $i = $start; $i <= $maxpages; $i++ ) {
			$j = 0;
			if ( $j <= 9 ) { 
        			print "<li><a href='" . $this->base . "review/phase" . $this->phase . "?items=" . $this->items . "&p=$i'>$i</a></li>";
			}
			$j++;
		} 

		print "<li><a href='" . $this->base . "review/phase" . $this->phase . "?items=" . $this->items . "&p=" . ($this->p + 1) . "' id='next'>Next</a></li></ul>
</div>";

	}

}


