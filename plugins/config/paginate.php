<?php

class Paginate{
	 
	
	public $currentPage,$perPage,$totalCount,$uri;
	
	public function __construct($perPage=50,$totalCount=0,$uri=''){
		$uri = empty($uri) ? $_SERVER['PHP_SELF'] : $uri ;
		$this->currentPage = (isset($_GET['page']) && ($_GET['page']>0))?(int)$_GET['page'] : 1 ;
		$this->perPage = (int)$perPage;
		$this->totalCount = (int)$totalCount;
		$this->uri = $uri ; 
	}
	
	public function totalPages(){
		return ceil($this->totalCount / $this->perPage);
	}
	
	public function offset(){
		return ($this->currentPage - 1) * $this->perPage;
	}
	
	public function previousPage(){
		return $this->currentPage - 1;
	}
	
	public function nextPage(){
		return $this->currentPage + 1;
	}
	
	public function hasPreviousPage(){
		return $this->previousPage() >= 1 ? true : false ;
	}
	
	public function hasNextPage(){
		return $this->nextPage() <= $this->totalPages() ? true : false ;
	}
	
	public function pageRel(){
		$uri = $this->uri;
		$page = $this->currentPage;
		$pageRel = '';
		$totalPage = $this->totalPages() ; 
		
		if(($page < 2) and ($totalPage > 1)){
			$pageRel = '<link rel="next" href="'.$uri.'/2" />';
			}
		else if(($page > 1) and ($page < $totalPage)){
			$pageRel = '
			 <link rel="prev" href="'.$uri.'/'.($page-1).'" />
			 <link rel="next" href="'.$uri.'/'.($page+1).'" />
			 ';
		}
		else if(($page == $totalPage) and ($totalPage > 1)){
			$pageRel = ' <link rel="prev" href="'.$uri.'/'.($page-1).'" />';
		}
		
		
		return $pageRel;
	}
	
	
	public function links(){
			$uri = $this->uri;
			$display="";
			if($this->totalPages() > 1){
				
				$display="<ul class='pagination pagination-md'>";
					 if($this->hasPreviousPage()){ 
						$display.="<li><a href='".$uri.ls.($this->currentPage - 1)."'>Previous</a> </li>";
					 };
						if($this->currentPage > 1){
							for($x=$this->currentPage-4;$x < $this->currentPage;$x++){
								if($x>0){
					$display.="<li><a href='".$uri.ls.$x."'>".$x."</a></li>";
					
						 } } }
					$display.="<li class='active'><a href='".$uri.ls.$this->currentPage."'>".$this->currentPage."</a></li>";
					
					 for($x=$this->currentPage+1; $x < $this->totalPages();$x++){ 
							
					
					$display.="<li><a href='".$uri.ls.$x."'>".$x."</a></li>";
					 if($x>= ($this->currentPage+4)){ break; }
					
					
					
					 } 
					
					 if($this->hasNextPage()){ 
						$display.="<li><a href='".$uri.ls.($this->currentPage + 1)."'>Next</a></li>";
					 }
				
				$display.="</ul>";
				return $display;
			} 
	}
	
	
	
	
	
	public function altLinks(){
			$uri = $this->uri;
			$display="";
			if($this->totalPages() > 1){
				$checkUrl = explode("?",$uri);
				count($checkUrl)>1 ? $uri.="&" : $uri.="?";
				
				$display="<ul class='pagination pagination-md'>";
					 if($this->hasPreviousPage()){ 
						$display.="<li><a href='".$uri."page=".($this->currentPage - 1)."'>Previous</a> </li>";
					 };
						if($this->currentPage > 1){
							for($x=$this->currentPage-4;$x < $this->currentPage;$x++){
								if($x>0){
					$display.="<li><a href='".$uri."page=".$x."'>".$x."</a></li>";
					
						 } } }
					$display.="<li class='active'><a href='".$uri."page=".$this->currentPage."'>".$this->currentPage."</a></li>";
					
					 for($x=$this->currentPage+1; $x < $this->totalPages();$x++){ 
							
					
					$display.="<li><a href='".$uri."page=".$x."'>".$x."</a></li>";
					 if($x>= ($this->currentPage+4)){ break; }
					
					
					
					 } 
					
					 if($this->hasNextPage()){ 
						$display.="<li><a href='".$uri."page=".($this->currentPage + 1)."'>Next</a></li>";
					 }
				
				$display.="</ul>";
				return $display;
			} 
	}
	
	
	
	
	
	
	
		public function paginateSearchLinks(){
			$uri = $this->uri;
			$display="";
			if($this->totalPages() > 1){
				$checkUrl = explode("?",$uri);
				count($checkUrl)>1 ? $uri.="&" : $uri.="?";
				
				$display="<ul class='pagination pagination-md'>";
					 if($this->hasPreviousPage()){ 
						$display.="<li><a href='".$uri."page=".($this->currentPage - 1)."'>Previous</a> </li>";
					 }
						if($this->currentPage > 1){
							for($x=$this->currentPage-4;$x < $this->currentPage;$x++){
								if($x>0){
					$display.="<li><a href='".$uri."page=".$x."'>".$x."</a></li>";
					
						 } } }
					$display.="<li class='active'><a href='".$uri."page=".$this->currentPage."'>".$this->currentPage."</a></li>";
					
					 for($x=$this->currentPage+1; $x < $this->totalPages();$x++){ 
							
					
					$display.="<li><a href='".$uri."page=".$x."'>".$x."</a></li>";
					 if($x>= ($this->currentPage+4)){ break; }
					
					 } 
					
					if($this->hasNextPage()){ 
						$display.="<li><a href='".$uri."page=".($this->currentPage + 1)."'>Next</a></li>";
					 }
					
				$display.="</ul>";
				return $display;
			} 
	}
		
}
	
	
	
 ?>