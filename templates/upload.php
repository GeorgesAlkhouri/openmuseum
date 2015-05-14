
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Upload Gallery Information</h3>
                    </div>
                    <div class="panel-body">

                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" id="name" placeholder="Picture Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="artist" placeholder="Artist Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="museum" placeholder="Museum Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="owner" placeholder="Owner Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="keywords" placeholder="Keyword1, Keyword2, Keyword3...">
                            </div>
                            <br />
                            <div class="form-group">
                                <label>Choose Category</label>
                                <select class="form-control">
                                    <option>Klassische Malerei</option>
                                    <option>Moderne Kunst</option>
                                    <option>Romantik</option>
                                    <option>Expressionismus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="picture">Select Picture</label>
                                <input type="file" id="picture">
                                <p class="help-block">Possible file types are TIFF, PNG and JEPG. The max file size is 20 MB.</p>
                            </div>
                            <br />
                        </form>

                        <div class="col-md-4"></div>
                        <div class="col-md-4">

                            <button type="submit" class="btn btn-default btn-lg">
                              <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Upload
                            </button>

                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>

            </div>
            <div class="col-md-2"></div>
        </div>
    </body>
</html>
