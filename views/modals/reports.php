<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><strong>Division</strong> Reports</h4>
</div>
<div class="modal-body">
	<p>This tool generates a copy of the bb-code skeleton for a division structure thread. It is up-to-date with all of the most recent changes on the forums at this time.</p>
	<pre class='well code' id='div-gen'><?php echo $division_structure; ?></pre>
	<small class="char-count text-muted"><strong><?php echo strlen($division_structure); ?></strong> characters of <strong>75000</strong> character limit</small>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
