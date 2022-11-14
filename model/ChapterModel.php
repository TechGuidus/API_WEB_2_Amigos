<?php
class ChapterModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=friends_db;charset=utf8', 'root', '');
    }

    function GetChapters($season = null)
    {
        if ($season !== "all") {
            $sentencia = $this->db->prepare("SELECT * FROM chapter Where id_season = ? ORDER BY emision_date ASC");
            $sentencia->execute(array($season));
        } else {
            $sentencia = $this->db->prepare(
                "SELECT 
                chapter.id id,
                chapter.title title,
                chapter.director director,
                chapter.writer writer,
                chapter.emision_date,          
                season.id id_season,
                season.season season_number   
                FROM 
                chapter INNER JOIN season ON chapter.id_season = season.id 
                ORDER BY season.season ASC"
            );
            $sentencia->execute();
        }
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    function InsertChapter($title, $chapter_number, $director, $writer, $description, $date, $id_season, $thumbnail_path)
    {
        if ($thumbnail_path !== NULL) {
            $sentencia = $this->db->prepare("INSERT INTO chapter(title,chapter_number,director,writer,description,emision_date,thumbnail_path,id_season) VALUES(?,?,?,?,?,?,?,?)");
            $sentencia->execute(array($title, $chapter_number, $director, $writer, $description, $date, $thumbnail_path, $id_season));
        } else {
            $sentencia = $this->db->prepare("INSERT INTO chapter(title,chapter_number,director,writer,description,emision_date,id_season) VALUES(?,?,?,?,?,?,?)");
            $sentencia->execute(array($title, $chapter_number, $director, $writer, $description, $date, $id_season));
        }
    }

    function UpdateChapter($title, $director, $writer, $description, $date, $chapter_number, $id, $thumbnail_path)
    {
        if ($thumbnail_path !== NULL) {
            $sentencia = $this->db->prepare('UPDATE chapter SET title=?,director=?,writer=?,description=?,emision_date=?,thumbnail_path=?,chapter_number=? WHERE id=?');
            $sentencia->execute(array($title, $director, $writer, $description, $date, $thumbnail_path, $chapter_number, $id));
        } else {
            $sentencia = $this->db->prepare('UPDATE chapter SET title=?,director=?,writer=?,description=?,emision_date=?,chapter_number=? WHERE id=?');
            $sentencia->execute(array($title, $director, $writer, $description, $date, $chapter_number, $id));
        }
    }

    function DeleteChapter($id)
    {
        $aux = $this->db->prepare("SELECT id_season FROM chapter WHERE id=?");
        $aux->execute([$id]);
        $sentencia = $this->db->prepare("DELETE FROM chapter WHERE id=?");
        $sentencia->execute(array($id));
        return $aux->fetch(PDO::FETCH_OBJ);
    }
    function DeleteAllChapters($id)
    {
        $sentencia = $this->db->prepare("DELETE FROM chapter WHERE id_season=?");
        $sentencia->execute(array($id));
    }
    function GetChapter($id)
    {
        $sentencia = $this->db->prepare("SELECT 
        chapter.id,
        chapter.title,
        chapter.chapter_number,
        chapter.director,
        chapter.writer,
        chapter.description,
        chapter.emision_date,
        chapter.thumbnail_path,
        chapter.id_season,
        FROM chapter
        WHERE chapter.id=?");
        $sentencia->execute(array($id));
        return $sentencia->fetch(PDO::FETCH_OBJ);
    }

}
//finished