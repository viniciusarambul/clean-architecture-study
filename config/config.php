<?php
$servidor = "db";

$usuario = "root";

$senha ="transaction";

$conexao = pg_connect('host=db user=root password=password dbname=transaction') or
die ("Não foi possível conectar ao servidor PostGreSQL");

