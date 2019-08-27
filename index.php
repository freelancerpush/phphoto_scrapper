<!DOCTYPE html>
<html lang="en">
<head>
  <title>BHp Scrapper</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }

    .loader {
    border: 10px solid #f3f3f3; /* Light grey */
    border-top: 10px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">BHP Scrapper</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <!-- <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Projects</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul> -->
    </div>
  </div>
</nav>
  
<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-2 sidenav">
      <a class="btn btn-primary" href="export_csv.php">Export To CSV</a>
    </div>
    <div class="col-sm-8 text-left"> 
     <br>
        <form>
        <div class="form-group">
          <label for="email">Enter Url:</label>
          <input type="pageuUrl" class="form-control" id="pageuUrl">
        </div>
       
        <button type="button" onclick="getProductUrls()" class="btn btn-default">Submit</button>
      </form>
      <br>
      <div class="col-sm-offset-5 " >
          <div class="loader hidden" id="progressbar_div"></div>
          <h1 class="hidden" id="progress">0%</h1>
       </div>
       <div class="col-sm-12 hidden" id="retry_div">
          <h1 class="pull-left"><span id="failed_product"></span> 
          <button type="button" onclick="retry()" class="btn btn-primary ">Retry</button></h1>
       </div>
   

    </div>
    <div class="col-sm-2 sidenav">
      <!-- <div class="well">
        <p>ADS</p>
      </div>
      <div class="well">
        <p>ADS</p>
      </div> -->
    </div>
  </div>
</div>

<footer class="container-fluid text-center">
  <p>F5Buddy</p>
</footer>

</body>
<script type="text/javascript">
  function getProductUrls()
  { 
      var pageUrl=$('#pageuUrl').val();
      if(pageUrl!="")
      { 
        $('#progressbar_div').removeClass('hidden');
        $('#progress').removeClass('hidden');
        
        setTimeout(function(){

          $.ajax({
          method: "POST",
          url: "geturl.php",
          async: false, 
          data: { pageUrl: pageUrl }
        }).done(function( data ) {
          
          var obj = JSON.parse(data);
          obj.length;
          var count = 1;

          $.each(obj, function( index, value ) {
            $.ajax({
              method: "POST",
              url: "product.php",
              async: false,
              data: { url: value }
            }).done(function( data ) {
              if(data){
              var percentage = 100/obj.length*count;             
              var percentage = Math.round(percentage);
              $('#progress').text(percentage+'%');
              count++;
              }
            });
          });
          debugger;
           $('#progressbar_div').addClass('hidden');
           $.ajax({
              method: "POST",
              url: "getfailedurl.php",
              async: false,
              data: {  }
            }).done(function( data1 ) {
                 if(data1 > 0)
                 {
                    $('#failed_product').text(data1+" Productu Failed");
                    $('#retry_div').removeClass('hidden');
                 }
                 else
                 {
                  $('#retry_div').addeClass('hidden');
                 }
            });
        });
         }, 3000);       
        
        

        
      }
      else
      {
        alert('Please insert page url');
      }
}
function retry()
  { 
       
        $('#progressbar_div').removeClass('hidden');
        $('#progress').removeClass('hidden');
        
        setTimeout(function(){

          $.ajax({
          method: "POST",
          url: "retry_getfailedurl.php",
          async: false, 
          data: {  }
        }).done(function( data ) {
          
          var obj = JSON.parse(data);
          obj.length;
          var count = 1;

          $.each(obj, function( index, value ) {
            $.ajax({
              method: "POST",
              url: "product.php",
              async: false,
              data: { url: value }
            }).done(function( data ) {
              if(data){
              var percentage = 100/obj.length*count;             
              var percentage = Math.round(percentage);
              $('#progress').text(percentage+'%');
              count++;
              }
            });
          });
           $('#progressbar_div').addClass('hidden');
           $.ajax({
              method: "POST",
              url: "getfailedurl.php",
              async: false,
              data: {  }
            }).done(function( data1 ) {
              //alert(data1)
                 if(data1 > 0)
                 {
                    $('#retry_div').removeClass('hidden');
                    $('#failed_product').text(data1+" Productu Failed");
                 }
                 else
                 {
                  $('#retry_div').addClass('hidden');
                 }
            });
        });
         }, 3000);       
        
        

        
      
}
</script>
</html>
