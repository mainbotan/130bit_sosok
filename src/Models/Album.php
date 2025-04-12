<?php
namespace App\Models;

class Album{
    public string $name;
    public string $id;
    public string $uri;
    public array $artists;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->uri = $data['uri'];
        $this->artists = json_decode($data['artists'], true);
    }
}