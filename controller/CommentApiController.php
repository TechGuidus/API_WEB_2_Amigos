<?php
require_once 'controller/ApiController.php';
require_once 'model/ApiModel.php';
require_once 'helpers/AuthAPIHelper.php';
require_once 'view/ApiView.php';


class CommentApiController extends ApiController
{
 
    private $helper;
    function __construct()
    {
        parent::__construct();
        $this->helper = new AuthAPIHelper();
    }

    function getComments($params = null)
    {
        if (isset($_GET['sortBy']) || isset($_GET['order'])) {
            if (isset($_GET['sortBy']) && isset($_GET['order'])) {
                if ($_GET['order'] == 'asc' || $_GET['order'] == 'ASC' || $_GET['order'] == 'desc' || $_GET['order'] == 'DESC') {
                    if (is_string($_GET['sortBy']) && in_array($_GET['sortBy'], $this->model->getCampos())) {
                        $comments = $this->model->getAll()($_GET['sortBy'], $_GET['order'], null, null, null, null);
                        if (!empty($comments))
                            $this->view->response($comments, 200);
                        else
                            $this->view->response('No Comments Found.', 404);
                    } else
                        $this->view->response('Field is not well written.', 400);
                } else
                    $this->view->response('Order Must be Ascending or Descending.', 400);
            } else {
                $this->view->response('Send all required parameters.', 400);
            }
        }
        else if (isset($_GET['size'])) {
            if (is_numeric($_GET['size'])) {
                $tamanio = isset($_GET['size']);
                for ($i = 0; $i < count($this->model->getAll()()) / $tamanio; $i++) {
                    $paginas[$i] = $this->model->getAll()(null, null, $i, $tamanio, null, null);
                }
                if (!empty($paginas))
                    $this->view->response($paginas);
                else
                    $this->view->response('No Comments Found.', 404);
            } else
                $this->view->response('Format Error', 400);
        }
    }

    function getComment($params = null)
    {
        $id = $params[':ID'];
        $comment = $this->model->get($id);
        if ($comment)
            $this->view->response($comment);
        else
            $this->view->response("The Comment with the ID $id does not exist.", 404);
    }

    function getchapterComments($params = null)
    {
        $id = $params[':ID'];
        $chapter = $this->model->getFromchapter($id);
        if ($chapter) {
            $this->view->response($chapter);
        } else {
            $this->view->response("The Chapter with the ID $id has no comments.", 404);
        }
    }

    function insertComment($params = null)
    {
        if ($this->helper->checkLoggedIn()) {
            $commentToAdd = $this->getData();
            if (empty($commentToAdd->content)) {
                $this->view->response('Unable to insert an empty comment.', 400);
            } else if (!isset($commentToAdd->id_chapter) || !is_numeric($commentToAdd->id_chapter)) {
                $this->view->response('Indicate which id_chapter does this comment belong to.', 400);
            } else {
                $success = $this->model->insert($commentToAdd->content, $commentToAdd->id_chapter);
                $this->view->response($success, 201);
            }
        } else {
            $this->view->response('Unauthorized.', 401);
        }
    }

    private function getInfo($showInfo)
    {
        $req = $this->model->getInfo();
        if (isset($showInfo)) {
            $this->view->response($req);
        } else {
            return $req;
        }
    }

    function editComment($params = null)
    {
        if ($this->helper->checkLoggedIn()) {
            $id = $params[':ID'];
            if (isset($id) && is_numeric($id)) {
                $commentToAdd = $this->getData();
                if (empty($commentToAdd->content))
                    $this->view->response('You cannot insert an empty comment.', 400);
                else {
                    $arr = $this->getInfo(false);
                    $contains = false;
                    foreach ($arr as $id_indiv) {
                        if ($id_indiv['id_chapter'] === $commentToAdd->id_chapter)
                            $contains = true;
                    }
                    if ($contains) {
                        $success = $this->model->edit($id, $commentToAdd->content, $commentToAdd->id_chapter);
                        $this->view->response($success);
                    } else
                        $this->view->response('Indicate a valid id_chapter.', 400);
                }
            }
        } else {
            $this->view->response('Unauthorized.', 401);
        }
    }

    function deleteComment($params = null)
    {
        $id = $params[':ID'];
        if (!isset($id))
            $this->view->response('Must provide a comment to delete.', 401);
        else
            $this->view->response($this->model->delete($id));
    }
}
