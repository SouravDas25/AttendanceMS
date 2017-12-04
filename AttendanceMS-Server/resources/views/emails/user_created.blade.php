<!DOCTYPE html PUBLIC >
<head>

<title></title>

</head>
<body>

<h1><a href="#">Online Attendance System</a></h1>
<span>Techno India College Of Technology</span> 

<div id="welcome" class="container">
	<div class="title">
		<h2><small>Welcome </small>, {{ $name }} </h2>
	</div>
	<p>Congratulations you have been successfully added to our System.</p>
	<p>With the following details</p>
	<p>userName : <strong>{{ $name }}</strong><br>
	Email : <strong>{{ $email }}</strong><br>
	<h3>Click here to set your password: {{ url('password/token/'.$token) }}</h3>
	<br>
	<h3>If you are not aware of Such subcription Click : {{ url('password/delete/'.$token) }}</h3>
	<p>
	We hope that you enjoy our services with ease.</p>
	<p>This is an auto generated email.</p>
</div>

</body>
</html>
