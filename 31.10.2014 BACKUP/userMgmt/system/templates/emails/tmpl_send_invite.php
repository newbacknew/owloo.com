<?php
$email['title'] = 'Invitation to {{website_name}}';
$email['body'] = '<h4>Invitation to {{website_name}}</h4>
<p>Hi there!<br />
{{username}} has sent you an invitation to join {{website_name}}!</p>

<p>To signup just go to <a href="{{site_url}}/signup.php">{{site_url}}/signup.php</a> and signup using this invitation code below:<br />
Invitation Code: <strong>{{invitecode}}</strong></p>
<hr />
<p>Regards,<br />
<a href="{{site_url}}">{{website_name}}</a></p>';
$email['variables'] = 'website_name,username,invitecode,site_url';
