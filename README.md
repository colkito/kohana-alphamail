# Kohana AlphaMail
A simple wrapper for AlphaMail library.

AlphaMail is a service to send transactional emails.
For more info see: `http://amail.io`.

### Properties:
- Flexible configuration using the groups
- Getting the Gravatar URL
- Getting the image tag
- Getting the profile data

### Usage example:
~~~
$amail = new AlphaMail();
~~~
Set email data:
~~~
$amail_project_id = 1234;
$amail_data = array(
	'title' => 'New iPhone',
	'price' => '10',
	'category' => 'Tech',
	);
~~~
Send email:
~~~
$amail->send('John Due', 'john.due@gmail.com', $amail_project_id, $amail_data);
~~~
