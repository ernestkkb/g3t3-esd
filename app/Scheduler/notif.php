<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
  <script src="push.js"></script>
  <link rel="stylesheet" type="text/css" href="styles.css">
  </head>

  
  <body>
  <div id="navbar"><span>Added POI</span></div>
  <div id="wrapper">
      <button id="notify-button">Add</button>
      <button id="clear-button">Clear All!</button>
      <button id="check-button">Check Permission</button>
  </div>
  
  <script>
      $("#notify-button").click(function(){
        Push.create("POI Added!",{
            body: "You have added your places of interest for the day.",
            icon: '/Logo_small.png',
            timeout: 2000,
            onClick: function () {
                window.focus();
                this.close();
            }
        });
      });
      $("#clear-button").click(function(){ 
           Push.clear();
      });
      $("#check-button").click(function(){ 
            console.log(Push.Permission.has());
      });
  </script>
  </body>
</html>