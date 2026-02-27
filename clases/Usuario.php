<?php
        class Usuario {
        public int $id_usuario;
        public string $email;
        public string $password;
        public string $nombre;
        public int $id_rol; // Relación con Rol

    public function __construct(int $id_usuario, string $email, string $password, int $id_rol, ?string $nombre = null) {
            $this->id_usuario = $id_usuario;
            $this->email = $email;
            $this->password = $password;
            $this->id_rol = $id_rol;
            $this->nombre = $nombre;
    }
}
?>