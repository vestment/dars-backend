<!DOCTYPE html>
<html>
<head>
<title>Add Remove input fields dynamically using jQuery in laravel 6.0</title>
</head>
<body>
<div class="input_fields_container_part">
<div><input type="text" name="tags">
    <button class="btn btn-sm btn-primary add_more_button">Add More Fields</button>
</div>
</div>
</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
var max_fields_limit = 8; //set limit for maximum input fields
var x = 1; //initialize counter for text box
$('.add_more_button').click(function(e){ //click event on add more fields button having class add_more_button
e.preventDefault();
if(x < max_fields_limit){ //check conditions
x++; //counter increment
$('.input_fields_container_part').append('<div><input type="text" name="tags"/><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div>'); //add input field
}
}); 
$('.input_fields_container_part').on("click",".remove_field", function(e){ //user click on remove text links
e.preventDefault(); $(this).parent('div').remove(); x--;
})
});
</script>