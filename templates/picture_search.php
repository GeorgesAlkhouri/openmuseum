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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <label>Picture Comparing</label>
                                            <input id="file_input" type="file" name="picture_comparing">
                                            <br />
                                        </div>
                                        <div class="row">
                                            <label>Picture Color Weight</label><br />
                                            <input name="color_weight" class="slider" data-slider-id='data-slider-color' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50">
                                        </div>
                                        <br />
                                        <div class="row">
                                            <label>Picture Shape Weight</label><br />
                                            <input name="shape_weight" class="slider" data-slider-id='data-slider-color' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50">
                                        </div>
                                        <br />
                                        <div class="row">
                                            <label>Picture Texture Weight</label><br />
                                            <input name="texture_weight" class="slider" data-slider-id='data-slider-color' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="50">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Color Comparing</label><small> (Click to select color or type in a color value)</small>
                                <div class="input-group cp">
                                    <input name ="picture_color" type="text" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
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

                $(".slider").on("change.slider",function(event){

                    $(".cp").colorpicker("disable");
                });

                $("#file_input").change(function() {

                    $(".cp").colorpicker("disable");
                });

                $('.cp').on('changeColor.colorpicker', function(event){

                    $("#file_input").attr('disabled',true);

                    //disable all sliders
                    //TODO: fix error
                    $(".slider").each(function() {
                            $(this).slider('disable');
                        })
                    });
                });
</script>
</body>
</html>
