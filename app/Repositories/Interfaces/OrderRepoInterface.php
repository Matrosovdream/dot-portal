<?php
namespace App\Repositories\Interfaces;

interface OrderRepoInterface {
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}