<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Results</h3>
            </div>
            <div id="panel-body" class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Picture Name</th>
                      <th>Creation Date</th>
                      <th>Artist</th>
                      <th>Owner</th>
                      <th>Museum Exhibition Date</th>
                      <th>Image<small> (Click for details)</small></th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      // print_r($this->_['results']);

                      foreach ($this->_['results'] as $picture) {

                        $artist = $picture->artist;
                        $owner = $picture->owner;

                        $base64 = base64_encode($picture->image_data);

                        $finfo = finfo_open();
                        $mime_type = finfo_buffer($finfo, $picture->image_data, FILEINFO_MIME_TYPE);

                        $host  = $_SERVER['HTTP_HOST'];
                        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = "index.php?view=picture"."&name=$picture->name"."&mime_type=".$mime_type;

                        $_SESSION["PICTURE_DATA"] = $picture->image_data;

                        echo "<tr>
                                <th>$picture->name</th>
                                <th>$picture->creation_date</th>
                                <th>$artist->firstname $artist->lastname</th>
                                <th>$owner->firstname $owner->lastname</th>
                                <th>From: $picture->museum_exhibits_startdate Unti:$picture->museum_exhibits_enddate</th>
                                <th><a href='http://$host$uri/$extra'><img width='45' height='45' src='data:$mime_type;base64,$base64' class='img-responsive' alt='Responsive image'></a></th>
                                <th>$picture->description</th>
                              </tr>";

                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
      </div>
    <div class="col-md-1"></div>
</div>
</body>
</html>
