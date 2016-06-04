<html>
<head>
<link rel="stylesheet" type="text/css" href="format.css">
<link rel="stylesheet" type="text/css" href="calendar.css">

  <!-- jQuery -->
  <script src="./jQuery/external/jquery/jquery.js"></script>
  <script src="./jQuery/jquery-ui.js"></script>
  <link rel="stylesheet" href="./jQuery/jquery-ui.css">
  
  
<title>
..::TeamSheet - Logging::..
</title>
</head>

<body>
  
  <div class="result" id="result" style="white-space: pre;overflow-y:scroll;overflow-x:hidden;height:500px;font-size:x-small;"> 
  <script>
    
    function reload(){
      $.get( "./logging/standard.log", "", function( data ) {
        $( ".result" ).html( data );
        var elem = document.getElementById('result');
        elem.scrollTop = elem.scrollHeight;
        //alert( "Load was performed." );
      });
    }
    
    window.setInterval(reload,1000);

  </script>
  </div>
  
</body>

</html>