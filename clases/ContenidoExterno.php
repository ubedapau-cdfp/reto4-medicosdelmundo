<?php
    class ContenidoExterno {
        public int $id_url;
        public string $url_externas;
        public int $id_bloque;

    public function __construct(int $id_url, string $url_externas, int $id_bloque) {
        $this->id_url = $id_url;
        $this->url_externas = $url_externas;
        $this->id_bloque = $id_bloque;
    }
    }
?>