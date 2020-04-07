<?php
    header('Content-type: text/html; charset=ISO-8859-1');

    $Estado = filter_input(INPUT_POST, 'Estado', FILTER_DEFAULT);
    $pdo = new PDO("mysql:host=localhost;dbname=intecbrasil_intec_bkp", "intecbrasil_intec", "010211admin123$#@!");
    $read = $pdo->prepare("SELECT *FROM tb_cidades_cid WHERE uf =:uf ORDER BY cidade");
    $read->bindParam(":uf", $Estado);

    $read->execute(array(
        ':uf' => $Estado
    ));
    $cidades = $read->fetchAll();
    
    foreach ($cidades as $cidade) { ?>
        <option value="<?=$cidade['cidade'] ?>"><?= $cidade['cidade'] ?></option>
    <?php } ?>
	