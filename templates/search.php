<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Search Gallery Information</h3>
            </div>
            <div id="panel-body" class="panel-body">
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="search-input">
                            <div class="row">
                                <label>Search for all picutre criteria</label>
                                <div class="input-group">
                                    <span class="input-group-addon">All</span>
                                    <input id="search_all" name="search_all" type="text" class="form-control" placeholder="Look for something on your mind...">
                                </div>
                                <br />
                            </div>
                            <div id="search_detail">
                            <div class="row">
                                <label>Search for given details</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Picture Name</span>
                                    <input name="search_picture_name"type="text" class="form-control" placeholder="Search for a picture name...">
                                </div>
                                <br />
                            </div>
                            <div class="row">
                                <div class="input-group">
                                    <span class="input-group-addon">Artist</span>
                                    <input name="search_picture_artist" type="text" class="form-control" placeholder="Search for an artist...">
                                </div>
                                <br />
                            </div>
                            <div class="row">
                                <div class="input-group">
                                    <span class="input-group-addon">Museum</span>
                                    <input name="search_picture_museum" type="text" class="form-control" placeholder="Search for a museum...">
                                </div>
                                <br />
                            </div>
                            <div class="row">
                                <div class="input-group">
                                    <span class="input-group-addon">Owner</span>
                                    <input name="search_picture_owner" type="text" class="form-control" placeholder="Search for an owner...">
                                </div>
                                <br />
                            </div>
                            <div class="row">
                                <div class="input-group">
                                    <span class="input-group-addon">Keywords</span>
                                    <input name="search_picture_keywords" type="text" class="form-control" placeholder="Search for keywords (Keyword 1, Keyword 2, ...)">
                                </div>
                                <br />
                            </div>
                            <div class="row">
                                <div class="input-group">
                                    <span class="input-group-addon">Description</span>
                                    <input name="search_picture_decription" type="text" class="form-control" placeholder="Describe the picture you are looking for...">
                                </div>
                                <br />
                            </div>
                        </div>
                        <div class="row">
                            <label>Choose Category</label>
                            <select id="category_select" name="search_category[]" multiple class="form-control">
                                <option>Klassische Malerei</option>
                                <option>Moderne Kunst</option>
                                <option>Romantik</option>
                                <option>Expressionismus</option>
                            </select>
                            <br />
                        </div>
                      </div>
                        <div class="row">
                            <div class="col-md-offset-5 col-md-2">
                                <button type="submit" class="btn btn-default btn-lg">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                                </button>
                            </div>
                        </div>
                        <!-- <input type="hidden" name="view" value="results" /> -->
                        <input type="hidden" name="action" value="search" />
                    </div>
            </div>
            </form>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $('#search_all').on('input', function(e) {
            if($('#search_all').val().length > 0) {

                $("#search_detail :input").attr("disabled", true);
                $("#category_select").attr("disabled", true);

            } else {

                $("#search_detail :input").attr("disabled", false);
                $("#category_select").attr("disabled", false);
            }
        });

        $('#search_detail :input').on('input', function(e) {

            var lengthZero = true;

            //check all input fields
            $('#search_detail :input').each(function () {
                if ($(this).val().length > 0)
                    lengthZero = false;
            });

            if(!lengthZero) {
                console.log("HERE");
              $("#search_all").attr("disabled", true);
            } else {

              if($('#search_detail :input').val().length == 0)
                  $("#search_all").attr("disabled", false);
            }
        });
    });
</script>
</body>
</html>
