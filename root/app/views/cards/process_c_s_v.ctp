<p>hello world </p>

<h1>File Name: <?php echo $fname; ?></h1>
<h1>File Size: <?php echo $fsize; ?></h1>
<h1>File Contents:</h1>

<table>
<tr><th>Question</th><th>Answer</th></tr>
<?php $answerRow = true; ?>
<?php foreach ($csvArray as $QorA): ?>
<?php if($answerRow){ ?>
<tr>
	<td><?php  echo $QorA;  ?></td>
<?php $answerRow=false; } else { ?>
	<td><?php  echo $QorA;  ?></td>
</tr>
<?php $answerRow = true; } ?>
<?php endforeach; ?>
</table>