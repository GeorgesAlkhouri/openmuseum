#Openmuseum

A multimedia **Oracle** database project with picture and color comparing and text indexing features.

The openmuseum web application is a study project. It allows users to upload pictures and add information for them which can be retrieved by classical text search or picture comparing and color comparing for content based retrieval.

##Technial

The application was tested with PHP 5.4.7 and needs [OCI8](http://php.net/manual/en/book.oci8.php) to communicate with Oracle.

Further, the project uses different Oracle retrieving technics:

-	ORDImage/ORDImageSignature
-	Context Indexing for CONTAINS usage

It also demonstrates the usage of the `sqlldr` utility tool in a [dockerized](https://www.docker.com) Oracle environment (see [dockerized_data_load](helper_not_in_project/dockerized_data_load) for detials).

The UI is created with [Bootstrap](http://getbootstrap.com) and external libraries to extent its functionality ([Colorpicker](http://mjolnic.com/bootstrap-colorpicker/), [Slider](https://github.com/seiyria/bootstrap-slider)).

###Side Note

All example pictures are labeled for noncommercial reuse.

Please keep in mind that this is a study project. So don't expect to much compfort.
