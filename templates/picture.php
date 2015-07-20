<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $this->_["name"]; ?></h3>
            </div>
            <div id="panel-body" class="panel-body">

              <?php

                $mime_type = $this->_["mime_type"];
                $base64 = base64_encode($this->_["picture_data"]);

                echo "<img src='data:$mime_type;base64,$base64' class='img-responsive center-block' alt='Responsive image'>"

              ?>

            </div>
        </div>
      </div>
    <div class="col-md-1"></div>
</div>
</body>
</html>
