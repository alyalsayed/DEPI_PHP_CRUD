<?php 

interface Crud
{
    public function create($data, $imageFiles = []);
    public function read($id);
    public function update($id, $data, $imageFiles = []);
    public function delete($id);
}

?>
