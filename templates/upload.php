
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Upload Gallery Information</h3>
                    </div>
                    <div class="panel-body">

                        <form action="index.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="Picture Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="artist" placeholder="Artist Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="museum" placeholder="Museum Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="owner" placeholder="Owner Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="keywords" placeholder="Keyword 1, Keyword 2, Keyword 3 ...">
                            </div>
                            <div class="form-group">
								<textarea class="form-control" name="description" rows="5" placeholder="Description Text"></textarea>
							</div>
                            <div class="form-group">
                                <label>Choose Category</label>
                                <select name="category[]" multiple class="form-control">
                                    <option>Klassische Malerei</option>
                                    <option>Moderne Kunst</option>
                                    <option>Romantik</option>
                                    <option>Expressionismus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="picture">Select Picture</label>
                                <input type="file" name="picture">
                                <p class="help-block">Possible file types are TIFF, PNG and JEPG. The max file size is 20 MB.</p>
                            </div>
                            <br />

                            <div class="col-md-4"></div>
                        		<div class="col-md-4">

	                            <button type="submit" class="btn btn-default btn-lg">
	                              <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Upload
	                            </button>

                        	</div>
                        	<div class="col-md-4"></div>

                        	<input type="hidden" name="view" value="upload" />
                        	<input type="hidden" name="action" value="upload" />
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-md-2"></div>
        </div>
    </body>
</html>
