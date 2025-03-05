<?php
$estado = $_POST['estado'];
$cidades = [];

if ($estado == 'SP') {
    $cidades = ['São Paulo', 'Campinas', 'Santos'];
} elseif ($estado == 'RJ') {
    $cidades = ['Rio de Janeiro', 'Niterói', 'São Gonçalo'];
}

foreach ($cidades as $cidade) {
    echo "<option value='$cidade'>$cidade</option>";
}
?>