<h3 class="popupTitle">Add a new note</h3>
<div id="previewNote" class="note yellow" style="left:0;top:65px;z-index:1">
	<div class="body"></div>
	<div class="author"></div>
	<span class="data"></span>
</div>
<div id="noteData"> <!-- Holds the form -->
<form action="" method="post" class="note-form">
<label for="note-body">Text of the note</label>
<textarea name="note-body" id="note-body" class="pr-body" cols="30" rows="6"></textarea>
<label for="note-name">Title</label>
<input type="text" name="note-name" id="note-name" class="pr-author" value="" />
<label>Color</label> <!-- Clicking one of the divs changes the color of the preview -->
<div class="color yellow"></div>
<div class="color blue"></div>
<div class="color green"></div>
<div class="color red"></div>
<div class="color pink"></div>
<div class="color grey"></div>
<div class="color lightGreen"></div>
<a id="note-submit" href="" class="btn">Add</a>
</form>
</div>