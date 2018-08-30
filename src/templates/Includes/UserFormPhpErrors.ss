<% if $ErrorList %>
<div class="error-container form-wide-errors" aria-hidden="true">
    <h4>Please correct the following errors and try again:</h4>
    <ul class="error-list">
    	<% loop $ErrorList %>
    		<li><a href="$Target">$Message</a></li>
    	<% end_loop %>
    </ul>
</div>
<% end_if %>