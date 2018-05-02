// logic for switching the rooms on the sidebar
// function clears the container for the calendars to display and then sets
// which calendar to show
function loadRoom(x) {
  hideDivs();
  clearDivs();
  switch (x) {
    case 0:
      document.getElementById("landing-page").style.display = "block";
      break;
    case 1:
      document.getElementById("110B").style.display = "block";
      document.getElementById("110Blink").style.backgroundColor = "#f2f2f2";
      document.getElementById("110Blink").style.color = "#000000";
      break;
    case 2:
      document.getElementById("110D").style.display = "block";
      document.getElementById("110Dlink").style.backgroundColor = "#f2f2f2";
      document.getElementById("110Dlink").style.color = "#000000";
      break;
    case 3:
      document.getElementById("114").style.display = "block";
      document.getElementById("114link").style.backgroundColor = "#f2f2f2";
      document.getElementById("114link").style.color = "#000000";
      break;

  default:
    console.log("Error loading page");
  }
}

// remove div from screen to make room for new content
function hideDivs() {
  document.getElementById("landing-page").style.display = "none";
  document.getElementById("110B").style.display = "none";
  document.getElementById("110D").style.display = "none";
  document.getElementById("114").style.display = "none";
}

// change background colors and text colors back to original
function clearDivs() {
  document.getElementById("110Blink").style.backgroundColor = "#999999";
  document.getElementById("110Dlink").style.backgroundColor = "#999999";
  document.getElementById("114link").style.backgroundColor = "#999999";

  document.getElementById("110Blink").style.color = "#ffffff";
  document.getElementById("110Dlink").style.color = "#ffffff";
  document.getElementById("114link").style.color = "#ffffff";
}

