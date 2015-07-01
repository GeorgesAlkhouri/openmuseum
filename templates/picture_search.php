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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <label>Picture Comparing</label>
                                            <input type="file" name="picture_comparing">
                                            <br />
                                        </div>
                                        <div class="row">
                                            <label>Picture Color Weight</label>
                                            <input class="slider" data-slider-id='data-slider-color' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50">
                                        </div>
                                        <br />
                                        <div class="row">
                                            <label>Picture Shape Weight</label>
                                            <input class="slider" data-slider-id='data-slider-color' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50">
                                        </div>
                                        <br />
                                        <div class="row">
                                            <label>Picture Texture Weight</label>
                                            <input class="slider" data-slider-id='data-slider-color' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Color Comparing</label>
                                <div class="input-group cp">
                                    <input name ="picture_color" type="text" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Texture Comparing</label>
                                    <input type="file" name="texture_comparing">
                                </div>
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
                        <input type="hidden" name="view" value="picture_search" />
                        <input type="hidden" name="action" value="picture_search" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
<script>
    $(function(){
    
                $('.cp').colorpicker();
    
                $(".slider").slider();
    });
</script>
</body>
</html>