# API

El objetivo de la API es el manejo de comentarios sobre los distintos episodios del sitio por medio de metodos HTTP como GET o POST.

Para evitar complicaciones, se decidio remover el front end del sitio ya que no era obligatorio desarrollarlo.

# Explicacion de los ENDPOINTS

GET (obtiene todos los comentarios): localhost/API_WEB_2_AMIGOS/comments

Inicia un GET de todo el contenido de la tabla Comment.

====================================================================================

POST (/:ID): localhost/API_WEB_2_AMIGOS/comments/add/

El comentario se manda atravez del metodo POST ya que esto permite la encriptación de los datos y evita que se pueda modificar por terceros en caso de un middleman. En caso de haber errores, los señaliza con su respectivo codigo de error.

====================================================================================

GET (/:ID): localhost/API_WEB_2_AMIGOS/comment/:ID || localhost/API_WEB_2_AMIGOS/chaptercomment/:ID

Permite saber de que episodio obtengo un comentario mientras haya relacion con el id_chapter

====================================================================================

DELETE (/:ID) localhost/API_WEB_2_AMIGOS/comments/del/:ID

En base a una ID se puede eliminar un comentario asociado.

====================================================================================

PUT (/:ID): localhost/API_WEB_2_AMIGOS/comments/:ID

Para editar el comentario, primero se va a tener que hacer un chequeo de ID del comentario para comprobar duplicacion u otro tipo de errores.