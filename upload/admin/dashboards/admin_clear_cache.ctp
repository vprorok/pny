<h2>Site Cache has been Cleared</h2>

<br>

<p>Results:</p>

<br>
<p>TMP dir: successfully deleted <?= $tmp_successes ?> file(s).
<br>
<?php
if (!empty($tmp_errors)) {
	?>Errors:<br><?php
	foreach ($tmp_errors as $error) {
		echo $error.'<br>';
	}
}
?>

<p>Cache dir: successfully deleted <?= $cache_successes ?> file(s).
<br>
<?php
if (!empty($cache_errors)) {
	?>Errors:<br><?php
	foreach ($cache_errors as $error) {
		echo $error.'<br>';
	}
}
?>


<p>Models dir: successfully deleted <?= $models_successes ?> file(s).
<br>
<?php
if (!empty($models_errors)) {
	?>Errors:<br><?php
	foreach ($models_errors as $error) {
		echo $error.'<br>';
	}
}
?>

</p>

