function filterByNameDateFunct(){
	var rows = document.getElementsByClassName("rowsOfTable");
	var x = document.getElementsByClassName("fullNamesInTable");
	var y = document.getElementsByClassName("startDates");
	var nameInput = document.getElementById("criteria-name");
	var dateInput = document.getElementById("criteria-date");
	for (var i = 0, len = rows.length; i < len; i++) {
		rows[i].style.display = "table-row";
	}

	//only when the user filters by start date
	if (dateInput.value!=""){
	var criteriaDate = dateInput.value;
	var help = criteriaDate;
	//adjusting the different date formats, one in javascript, other from the form and third from the database
	criteriaDate = criteriaDate.replaceAt(0, help.substr(3,1));
    criteriaDate = criteriaDate.replaceAt(1, help.substr(4,1));
    criteriaDate = criteriaDate.replaceAt(3, help.substr(0,1));
    criteriaDate = criteriaDate.replaceAt(4, help.substr(1,1));
    var finalDate = new Date(criteriaDate);
	}

	for (var i = 0, len = rows.length; i < len; i++) {
    			if (x[i].innerHTML.toUpperCase().indexOf(nameInput.value.toUpperCase()) == -1) rows[i].style.display = "none";
    			if (dateInput.value!=""){
    				temp2 = new Date(y[i].innerHTML);
    				if (temp2.getTime() != finalDate.getTime()) rows[i].style.display = "none";
    			}

    }
}

String.prototype.replaceAt=function(index, replacement) {
    return this.substr(0, index) + replacement+ this.substr(index + replacement.length);
}



