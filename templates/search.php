<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Search Gallery Information</h3>
            </div>
            <div id="panel-body" class="panel-body">
                <form>
                    <div id="searchInput">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">All<span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Artist</a></li>
                                    <li><a href="#">Owner</a></li>
                                    <li><a href="#">Keywords</a></li>
                                    <li><a href="#">Description</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">All</a></li>
                                </ul>
                            </div>
                            <!-- /btn-group -->
                            <input type="text" class="form-control" aria-label="...">
                            <div class="input-group-btn">
                                <button id="addButton" type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        <!-- /input-group -->
                        </br >
                    </div>
                    <div id="searchInputFilling"></div>
                    <div class="col-md-12">
                        <label>Choose Category</label>
                        <select name="category[]" class="form-control">
                            <option>Klassische Malerei</option>
                            <option>Moderne Kunst</option>
                            <option>Romantik</option>
                            <option>Expressionismus</option>
                        </select>
                        </br>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Picture Comparing</label>
                            <input type="file" name="picture_comparing">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Color Comparing</label>
                        <div class="input-group cp">
                            <input name ="picture_color" type="text" value="#5367ce" class="form-control" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Texture Comparing</label>
                            <input type="file" name="texture_comparing">
                        </div>
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
    });
    var count = 1;
    
     $('#addButton').on('click', function () {
     			if (count < 4) {
    
       			$( "#searchInputFilling" ).append( $("#searchInput").html() );
       			count++;
    
       		}
       	});
</script>
</body>
</html>