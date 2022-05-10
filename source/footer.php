	</div> <!-- container -->
	<div class="modal fade" id="debug" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-xl" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Debug Info</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <pre><?php
					echo "_GET\n";
					print_r($_GET);
					echo "_POST\n";
					print_r($_POST);
					echo "_FILES\n";
					print_r($_FILES);
					echo "_SERVER\n";
					print_r($_SERVER);
					echo "_SESSION\n";
					print_r($_SESSION);
					echo "Site\n";
					print_r($site);
					echo "-->\n";
	        ?>
	        </pre>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
	<footer id="footer">&copy;Copyright <?=date('Y')?> <?=$site->config->title?></footer>
</div><!-- Page-->
<?php
    if($site->config->debug){
        ?>
    <button class="btn btn-info material-icons-outlined md-inactive" style="right: 0; bottom: 0; position: absolute;" data-toggle="modal" data-target="#debug">bug_report</button>
    <?php
    }
    ?>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="<?=$site->config->base?>/scripts/pwv.js"></script>
</body>
</html>
