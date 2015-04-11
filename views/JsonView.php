<?php
/**
 * @desc To output the response in JSON
 * @author Paul Doelle, 29/03/15
 */

class JsonView extends ApiView {
    public function render($content) {
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($content);
        return true;
    }
}