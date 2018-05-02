/*
	Variable section of reservation.js ----------------------------------------------
*/

// variables to be used
var s_date, e_date, s_time, e_time, myStartDate, myEndDate, myRoom, roomComments, roomReason, eventId;

// classroom ID variables
var room110Bid = 'vrhlnmqrp3va3b01qmqsbrmo8o@group.calendar.google.com';
var room110Did = '49u6s1ln2fk3h78hbvfgldsie8@group.calendar.google.com';
var room114id = 'vi70qaf17scmc1icgpbncg083s@group.calendar.google.com';
var roomToUseId;

// Client ID and API key from the Developer Console
var CLIENT_ID = '265344027909-u3o85nqr74844r1ctjbgg3cb485il10s.apps.googleusercontent.com';
var API_KEY = 'AIzaSyDV84rGNOX742_xTlhbv_IUU_AL71E_9vY';

// Array of API discovery doc URLs for APIs used by the quickstart
var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

// Authorization scopes required by the API
var SCOPES = "https://www.googleapis.com/auth/calendar";

/*
	Functions part of reservation.js ------------------------------------------------
*/

// called from reservation submit button click
function add_res(form, type) {
	/*Start Date*/
	s_date = document.getElementById("s-date").value;
	/*End Date*/
	e_date = document.getElementById("e-date").value;
	/*Start Time in 24 hour time*/
	s_time = document.getElementById("s-time").value;
	/*End Time in 24 hour time*/
	e_time = document.getElementById("e-time").value;

	myStartDate = new Date(s_date + " " + s_time);
	myEndDate = new Date(e_date + " " + e_time);

	// event ID
	eventId = Math.random().toString(36).substring(7);
	console.log(eventId);

	toDatabase(form, type);
}

// sends data to database
function toDatabase(form, type) {
	var sel = document.getElementById("room").value;
	var room_id = -1;
	console.log(sel);
	/*Sets the room ID*/
	if(sel == 1) {
		room_id = 334;
		roomToUseId = room114id;
		myRoom = 'McAdams 114';
    //break;
	} else if(sel == 2) {
		room_id = 331;
		roomToUseId = room110Bid;
		myRoom = 'McAdams 110B';
    //break;
	} else if(sel == 3) {
		room_id = 332;
		roomToUseId = room110Did;
		myRoom = 'McAdams 110D';
    //break;
	} else {}

	/*Will want to parse the array to get values to send to server*/
	var day = $('#repeat').val();
	var days = "";
	for (var i=0; i < day.length; i++) {
		if(day[i] == 1) {
			days += 'M';
		} else if(day[i] == 2){
			days += "T";
		} else if(day[i] == 3){
			days += "W";
		} else if(day[i] == 4){
			days += "R";
		} else if(day[i] == 5){
			days += "F";
		} else if(day[i] == 6){
			days += "S";
		} else if(day[i] == 7){
			days += "U";
		} else {
			break;
		}
	}
	/*Gets the reason for the reservation*/
	var reason_box = document.getElementById("reason");
	var reason_val = reason_box.options[reason_box.selectedIndex].text;
	roomReason = reason_val;
	/*Gets Addidional Comments*/
	var comments = document.getElementById("a-comments").value;
	roomComments = comments;

  // pull values and send to the .php to be sent to the database
	if(type=="add")
	{
		jQuery.ajax({
		    type: "POST",
		    url: 'scripts/pass.php',
		    dataType: 'json',
		    data: {functionname: 'addRes', room: room_id, start_dt: s_date, end_dt: e_date,
		    								start_tm: s_time, end_tm: e_time, day: days,
		    								reason: reason_val, comment: comments, event_id: eventId},

		    success: function (obj, textstatus) {
            // if successful, then send user to home page and send the event to Google
		    	if( !('error' in obj) ) {
						console.log(obj);
		    		window.location.href="index.html";
						alert('Congrats! Your reservation was successful!');
						insertToGoogle();
					}
					else {
						alert('Sorry...your reservation was denied.');
					}
					console.log(obj);
			}
	  });
	}
  // updating a reservation 
  else {
		var res_Id = document.getElementById("resId").value;
		jQuery.ajax({
		    type: "POST",
		    url: 'scripts/pass.php',
		    dataType: 'json',
		    data: {functionname: 'update_res', resId: res_Id, room: room_id, start_dt: s_date, end_dt: e_date,
		    								start_tm: s_time, end_tm: e_time, day: days,
		    								reason: reason_val, comment: comments},

		    success: function (obj, textstatus) {
            // if successful, then send user to home page and update the event in Google
		    	if( !('error' in obj) ) {
						console.log(obj);
						console.log(obj.event_id);
		    		window.location.href="index.html";
						alert('Congrats! Your reservation update was successful!');
						patchToGoogle(obj.event_id);
					}
					else {
						alert('Sorry...your reservation update was denied.');
					}
					console.log(obj);
				}
	  });
	}
}

// if the user hits the Enter key
var elem = document.getElementById("a-comments");
elem.addEventListener("keyup", function(event) {
	event.preventDefault()
	if (event.keyCode === 13) {
		add_res(document.getElementById("add_res"));
	}
});

// ----------------------------------------------------------------------------
// logic for adding an event to the google calendar

/**
 *  On load, called to load the auth2 library and API client library.
 */
function handleClientLoad() {
	gapi.load('client:auth2', initClient);
}

/**
 *  Initializes the API client library and sets up sign-in state
 *  listeners.
 */
function initClient() {
	gapi.client.init({
		apiKey: API_KEY,
		clientId: CLIENT_ID,
		discoveryDocs: DISCOVERY_DOCS,
		scope: SCOPES
	}).then(function () {
		handleAuthClick();
	}),function (error) {
		console.log(error);
	};
}

/**
 *  Sign in the user
 */
function handleAuthClick() {
	gapi.auth2.getAuthInstance().signIn();
}

// updates / patches an events
function patchToGoogle(e_id) {
	var event = gapi.client.calendar.events.get({"calendarId": roomToUseId, "eventId": e_id});

  // set changed fields
  event.location = myRoom;
	event.summary = roomReason;
	event.description = roomComments;
	event.start = {
		'dateTime': myStartDate.toISOString(),
		'timeZone': 'America/New_York'
	};
	event.end = {
		'dateTime': myEndDate.toISOString(),
		'timeZone': 'America/New_York'
	}

	// send patch ot google calendar
  var request = gapi.client.calendar.events.patch({
      'calendarId': roomToUseId,
      'eventId': e_id,
      'resource': event
  });

  request.execute(function (event) {
     console.log(event);
  });
}

// code to create an event
function insertToGoogle() {

	// create event to send
	var event = {
	  'summary': roomReason,
	  'location': myRoom,
	  'description': roomComments,
		'id': eventId,
	  'start': {
	    'dateTime': myStartDate.toISOString(),
			'timeZone': 'America/New_York'
	  },
	  'end': {
	    'dateTime': myEndDate.toISOString(),
			'timeZone': 'America/New_York'
	  }
	};

	// insert event into appropriate calendar
	var request = gapi.client.calendar.events.insert({
	  'calendarId': roomToUseId,
	  'resource': event
	});

	request.execute(function(event) {
		console.log(event);
	  appendPre('Event created: ' + event.htmlLink);
	});
}
