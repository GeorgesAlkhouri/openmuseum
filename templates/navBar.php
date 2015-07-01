<body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
   <!-- Include all compiled plugins (below), or include individual files as needed -->
   <script src="js/bootstrap.min.js"></script>
   <script src="vendor/colorpicker/js/bootstrap-colorpicker.min.js"></script>
   <script src="vendor/slider/js/bootstrap-slider.js"></script>

 <nav class="navbar navbar-default">
 <div class="container-fluid">
   <!-- Brand and toggle get grouped for better mobile display -->
   <div class="navbar-header">
     <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
       <span class="sr-only">Toggle navigation</span>
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
     </button>
     <a class="navbar-brand" href="#">openmuseum</a>
   </div>
   <!-- Collect the nav links, forms, and other content for toggling -->
   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     <ul class="nav navbar-nav">
       <li <?php if ($this->_['active'] === 'search') echo 'class="active"'; ?>><a href="?view=search">Text Search<span class="sr-only">(current)</span></a></li>
       <li <?php if ($this->_['active'] === 'picture_search') echo 'class="active"'; ?>><a href="?view=picture_search">Picture Comparison</a></li>
       <li <?php if ($this->_['active'] === 'upload') echo 'class="active"'; ?>><a href="?view=upload">Upload</a></li>
     </ul>
     <div class="navbar-right">
       <p class="navbar-text">&copy; Natascha Fadeeva, Georges Alkhouri</p>
     </div>
   </div><!-- /.navbar-collapse -->
 </div><!-- /.container-fluid -->
</nav>
