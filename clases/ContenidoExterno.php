<?php
    class ContenidoExterno {
        public $id_url;
        public $url_externas;
        public $id_bloque;

    public function __construct($id_url, $url_externas, $id_bloque) {
        $this->id_url = $id_url;
        $this->url_externas = $url_externas;
        $this->id_bloque = $id_bloque;
    }
    }
?>