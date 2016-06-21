var initAutocomplete;
var fillInAddress;
var fillInAddress2;
var geolocate;
$(document).ready(function(){


      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete,autocomplete2,autocomplete3;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };


	var componentForm2 = {
        street_number2: 'short_name',
        route2: 'long_name',
        locality2: 'long_name',
        administrative_area_level_2: 'short_name',
        country2: 'long_name',
        postal_code2: 'short_name'
      };


      initAutocomplete = function() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);






	  autocomplete2 = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete2')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete2.addListener('place_changed', fillInAddress2);



  autocomplete3 = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete3')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete3.addListener('place_changed', fillInAddress3);



	  }



fillInAddress3 = function() {
        // Get the place details from the autocomplete object.
        var place3 = autocomplete3.getPlace();

 
	
        

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place3.address_components.length; i++) {
		
          var addressType = place3.address_components[i].types[0];
 
          if (componentForm[addressType]) {
            var val = place3.address_components[i][componentForm[addressType]];
		
	      $("."+addressType).each(function(){$(this).val(val);})		
             
          }
        }
      }




fillInAddress2 = function() {
        // Get the place details from the autocomplete object.
        var place2 = autocomplete2.getPlace();

 
	
        for (var component in componentForm2) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }


        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place2.address_components.length; i++) {
		
          var addressType = place2.address_components[i].types[0];
 
          if (componentForm[addressType]) {
            var val = place2.address_components[i][componentForm[addressType]];
		
             $("."+addressType).val(val);
          }
        }
      }



      fillInAddress = function() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      geolocate= function() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
		
          });
        }
      }

});

 
