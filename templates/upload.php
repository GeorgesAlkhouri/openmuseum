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
                        <label>Picture</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="picture_name" placeholder="Picture Name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="picture_creation_date" placeholder="Date Of Creation (dd.MM.YYYY)">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="keywords" placeholder="Keyword 1, Keyword 2, Keyword 3 ... (max. 10)">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" name="description" rows="5" placeholder="Description Text"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choose Category (optional)</label>
                                <select name="category[]" multiple class="form-control">
                                    <option value="2">Klassische Malerei</option>
                                    <option value="3">Moderne Kunst</option>
                                    <option value="4">Romantik</option>
                                    <option value="6">Expressionismus</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Artist</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="artist_firstname" placeholder="Firstname">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="artist_surname" placeholder="Surname">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="artist_birthday" placeholder="Birthday (dd.MM.YYYY)">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="artist_deathday" placeholder="Deathday (dd.MM.YYYY)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Museum (optional)</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control museum_input" name="museum_name" placeholder="Name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control museum_input" name="museum_adress" placeholder="Adress">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control museum_input" name="museum_website" placeholder="Website">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-1">
                                <input id="museum_isExhibitor" type="checkbox" name="museum_isExhibitor">
                            </div>
                            <div class="col-md-2">
                                <span>Does museum exhibit picture?</span>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-1">
                                <input id="checkbox2" type="checkbox" name="museum_isOwner">
                            </div>
                            <div class="col-md-2">
                                <span>Is museum also owner?</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Owner</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control disableable" name="owner_firstname" placeholder="Firstname">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control disableable" name="owner_surname" placeholder="Surname">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="picture">Select Picture</label>
                        <input type="file" name="picture">
                        <p class="help-block">Possible file types are TIFF, PNG and JEPG. The max file size is 5 MB.</p>
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
<script type="text/javascript">
    $(document).ready(function(){

        $("input.museum_input").keypress(function() {

            $("#museum_isExhibitor").attr('checked', 'checked');
        });

        // $("#museum_isExhibitor").change(function(){
        //
        //     if ("input.museum_input").each(function(){
        //
        //     });
        // });

        $('#checkbox2').change(function() {
            if($(this).is(":checked")) {
                $("input.disableable").attr("disabled", true);
                $("input.disableable").val("");
            } else {
                $("input.disableable").removeAttr("disabled");
            }
        });
    });
</script>
</body>
</html>
