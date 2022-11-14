<?php

class ApiModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=friends_db;charset=utf8', 'root', '');
    }

    function getAll($sortBy = null, $order = null, $paginas = null, $tamanio = null)
    {
        if (isset($sortBy) && isset($order)) {
            $req = $this->db->prepare("SELECT id, content, id_chapter FROM comment LEFT JOIN chapter ON comment.id_chapter=chapter.id ORDER BY $sortBy $order");
            $req->execute();
        }
        else if (isset($pagina) && isset($tamanio)) {
            $req = $this->db->prepare("SELECT id, content, id_chapter FROM comment LEFT JOIN chapter ON comment.id_chapter=chapter.id ORDER BY (SELECT NULL) OFFSET $paginas*$tamanio ROWS FETCH NEXT $tamanio ROWS ONLY");
            $req->execute();
        }
        else {
            $req = $this->db->prepare('SELECT id, content, id_chapter FROM comment LEFT JOIN chapter ON comment.id_chapter=chapter.id');
            $req->execute();
        }
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function getCampos()
    {
        $req = $this->db->query('SELECT * FROM comment LIMIT 0');
        for ($i = 0; $i < $req->columnCount(); $i++) {
            $col = $req->getColumnMeta($i);
            $columnas[] = $col['name'];
        }
        return $columnas;
    }

    function get($id)
    {
        $req = $this->db->prepare('SELECT id, content, id_chapter FROM comment LEFT JOIN chapter ON comment.id_chapter=chapter.id WHERE id = ?');
        $req->execute([$id]);

        return $req->fetch(PDO::FETCH_OBJ);
    }

    function getFromchapter($id)
    {
        $req = $this->db->prepare('SELECT id, content, comment.id_chapter, chapter FROM comment LEFT JOIN chapter ON comment.id_chapter=chapter.id WHERE id_chapter = ?');
        $req->execute($id);
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    function insert($content, $id_chapter)
    {
        if ($id_chapter) {
            $req = $this->db->prepare('INSERT INTO comment (content, id_chapter) VALUES (?,?)');
            $req->execute([$content, $id_chapter]);
            return $this->get($this->db->lastInsertId());
        }
        return null;
    }

    function getInfo()
    {
        $req = $this->db->prepare('SELECT id, chapter_number FROM chapter');
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_OBJ);
        return $res;
    }

    function edit($id, $content, $id_chapter)
    {
        $req = $this->db->prepare('UPDATE comment SET content = ?, id_chapter = ? WHERE id = ?');
        $req->execute([$content, $id_chapter, $id]);
        return $this->get($id);
    }

    function delete($id)
    {
        $commenttoDel = $this->get($id);
        $req = $this->db->prepare('DELETE FROM comment WHERE id = ?');
        $req->execute([$id]);
        return $commenttoDel;
    }
}
