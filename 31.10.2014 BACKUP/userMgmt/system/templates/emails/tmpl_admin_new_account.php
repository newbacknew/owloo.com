<?php
$email['title'] = 'Account Details';
$email['body'] = '<h4>Account Details</h4>
<p>Your account has been created. You may now login by going to the website {{site_url}}, and use the login details below.</p>
<p>Username: <b>{{username}}</b><br />
Password: <b>{{password}}</b></p>
<p>You can change your password once your are logged in, on the account settings page.</p>
<p>Regards,<br /><a href="{{site_url}}">{{website_name}}</a></p>';
$email['variables'] = 'website_name,site_url,username,email,newpassword,visitor_ip';
