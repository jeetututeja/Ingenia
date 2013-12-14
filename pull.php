<?php 
$gitpull = shell_exec('git pull 2>&1');
$gitlog = shell_exec('git log --stat -1');
$ls = shell_exec('ls -lar');
echo "<pre>$gitpull</pre>\n<pre>$gitlog</pre>\n<pre>$ls</pre>";
?>