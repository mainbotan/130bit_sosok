<?php
namespace App\DTO;

class AlbumCreateDTO{
    public string $name;
    public string $id;
    public string $uri;
    public string $artists;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->uri = $data['uri'];
        $this->artists = json_encode($data['artists'], true);
    }
}