function removeElement(element) {  
	jQuery(element).remove();  
}  			
													
var elementCounter = jQuery("input[name=element-max-id]").val();

function setElementId(element, id) {  
	var newId = "styles-element-" + id;  
	jQuery(element).attr("id", newId);  
	var inputField = jQuery("input", element);  
	inputField.attr("name", "style-id-" + id);  
	var labelField = jQuery("label", element);  
	labelField.attr("for", "style-id-" + id);  
} 

jQuery(document).ready(function() {  
	jQuery("#styles-list").sortable( {  
		stop: function(event, ui) {  
			var i = 0;  
			jQuery("li", this).each(function() {  
				setElementId(this, i);  
				i++;  
			});  
			elementCounter = i;  
			jQuery("input[name=element-max-id]").val(elementCounter);  
		}  
	});
	jQuery("#add-style").click(function() {
		
		var elementRow = jQuery("#styles-element-placeholder").clone();  
		
		var newId = "styles-element-" + elementCounter;  
		elementRow.attr("id", newId);  
		elementRow.show();  
		
		var inputStyleField = jQuery('input[name="style-id"]', elementRow);
		inputStyleField.attr("name", "style-id-" + elementCounter);  
		
		var inputClassField = jQuery('input[name="class-id"]', elementRow);
		inputClassField.attr("name", "class-id-" + elementCounter);  
		
		var labelStyleField = jQuery('label[for="style-id"]', elementRow);  
		labelStyleField.attr("for", "style-id-" + elementCounter);  
		
		var labelStyleField = jQuery('label[for="class-id"]', elementRow);  
		labelStyleField.attr("for", "class-id-" + elementCounter);  
		
		var removeLink = jQuery("a", elementRow).click(function() {  
			removeElement(elementRow);  
			return false;  
		});  
		
		elementCounter++;  
		
		jQuery("input[name=element-max-id]").val(elementCounter);  
		jQuery("#styles-list").append(elementRow);  
		return false;  
	});  
});  

