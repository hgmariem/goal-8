<!DOCTYPE html>
<html>
<head>
	<title>PDF Template</title>
</head>
<body>
	<?php
foreach ($attributes as $key => $value)
{
?>
<h3>{{$value['title']}}</h3>
<p>Sheet Name : - {{$value['sheet_name']}}</p>
<p>{!! $value['html'] !!}</p>

<?php	
}
	?>
</body>
</html>