<div id="cont">
    <h2>Contact Us</h2>
    <form action="<?php echo site_url('pages/contact'); ?>" method="post" id="contactform">
    <table>
        <tr>
            <td class="label">First Name:</td><td><input required="true" name="first_name" type="text" /></td>
        </tr>
         <tr>
             <td class="label">Last Name:</td><td><input type="text" name="last_name" /></td>
        </tr>        
        <tr>
             <td class="label">Email:</td><td><input required="true" type="text" name="email" /></td>
        </tr>
        <tr>
             <td class="label">Phone:</td><td><input type="text" name="phone" /></td>
        </tr>
        <tr>
            <td class="label">Your Message:</td><td><textarea name="message" required="true" rows="5" cols="10"></textarea></td>
        </tr>
        <tr> <td></td><td> </td>  </tr>
                <tr> <td></td><td> </td>  </tr>
        <tr>
            <td></td><td>
                <a onclick="$('#contactform').submit()" href="javascript:;" class="button"><span>Submit</span></a>
            </td>
        </tr>
    </table>
    
    <br/><br/>
    <div style="float: left; padding-left: 139px;" >
<b>Address:</b><br/>
800, Pocket - 6, Sec - 2<br/>
Rohini, New Delhi.<br/>
Pin: 110085<br/>
Phone no. +91 9871557789<br/>
</div>
</form>
</div>

<?php add_scripts(array('static/js/jquery.validate.min')); ?>
<script src="http://code.jquery.com/ui/jquery-ui-git.js"></script>
<script type="text/javascript">
    var v = $("#contactform").validate({
            messages: {
                email: {required: 'Please input your email id!'},
                first_name:{required:'Atleast First name is required.'},
                message:{required:'Enter few lines of message.'}
            }

    });
</script>