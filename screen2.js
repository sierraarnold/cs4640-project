// Rick kim

// Variables
var categories = new Array();
var initialValue = document.getElementById('initialVal');

initialValue.addEventListener("keyup", error);

// Categories
categories[0] = "Length";
categories[1] = "Area";

// Functions
function update_searchbox(search_list, categories_array) {
    search_list.length = categories_array.length;
    for (i = 0; i < categories_array.length; i++) {
        search_list.options[i].text = categories_array[i];
    }
}

// Anonymous function
(function() {
    alert('Welcome to our HoosConvert!');
})();

// Arrow function, resets the values to 0 when error occurs
let resetVal = (val) => val.value = 0;

function error(){ // Error message
   if(initialValue.value < 0) {
       resetVal(initialValue);
       alert("Error: You have entered an invalid number. Please try again.")
   }
}

// Actual calculations
document.getElementById('result').style.visibility = "hidden"; // Hides the output boxes until the user enters values
document.getElementById('initialVal').addEventListener('input',
function(e){
    error(); // Checks for validity
    document.getElementById('result').style.visibility = "visible"; // Shows the boxes
    let km = e.target.value;
    // Calculations
    document.getElementById('resultInMt').innerHTML = km * 1000;
    document.getElementById('resultInFt').innerHTML = km * 3280.8398950131;
    document.getElementById('resultInMi').innerHTML = km / 1.609344;
})

// How the web application will initially look
window.onload = function(e) {
    update_searchbox(document.categories_list.dropdown_list, categories);
}