<!-- Footer -->
<?php
if(isset($params->noheaders))
		if($params->noheaders == 1)
			return;
?>
<br>
<footer class="text-center page-footer font-small blue">
	<div style="line-height: 80%;" class="footer-copyright">
			<li style="display: inline;"><a href="#">
				<i class="fab fa-twitter fa-sm"></i>
			</a>
			&nbsp
			</li>
			<li style="display: inline;"><a href="https://www.linkedin.com/in/mumtazahmad2">
				<i class="fab fa-linkedin fa-sm"></i>
			</a>
			&nbsp
			</li>
			<li style="display: inline;"><a href="#">
			   <i class="fas fa-globe fa-sm"></i>
			</a>
			&nbsp
			</li>
			<li style="display: inline;"><a href="mailto:mumtaz_ahmad@mentor.com">
				<i class="fas fa-envelope fa-sm"></i>
			</a>
			</li>
			<br>
			<small style="margin-top:-200px;font-size:8px;">
			<?php
				$trello = new Trello();
				echo "Last updated ".$trello->GetLastUpdatedOn();
			?>
			</small>
	
	</div>
</footer>