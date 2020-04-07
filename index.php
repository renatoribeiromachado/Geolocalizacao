<?php
//if (!class_exists('Login')) :
//        header('Location: ../../imp.php');
//        die;
//    endif;
?>
<!DOCTYPE html >
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="shortcut icon" href="https://www.acessohost.com.br/favicon/favicon.png" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="css/ui-lightness/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/reset.css" rel="stylesheet"/>
    <link href="css/admin.css" rel="stylesheet"/>
    <title>GEOLOCALIZAÇÃO</title>  
</head>
<html>
    <body>
        <!--HEADER-->
        <div class="container-fluid parallax2">
            <div class="col-md-12">
                <?php include "https://www.intecbrasil.com.br/Intec/admin/inc/header.php";?>
            </div>
            <!--MENU BRAND-->   
            <div class="col-md-3 navbar navbar-default">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle bg-danger" data-toggle="collapse" data-target="#permission">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="permission">
                    <ul class="nav navbar-nav">
                        <!--RETURN-->
                        <li role="presentation">
                            <a href="https://www.intecbrasil.com.br/Intec/admin/painel.php" title="Sistema Intec">
                                <i class="glyphicon glyphicon-home"></i> RETORNAR AO SISTEMA INTEC
                            </a>
                        </li>
                    </ul>
                </div> 
            </div>
            
            <div class="col-md-12">  
                <?php
                    echo "<p class='text-white'><b>São Paulo - ",getdate()['weekday'], ', ', getdate()['mday'], ' ', getdate()['month'], ' ', getdate()['year'], "</b></p>";
                ?> 
            </div>
            
            <div class="col-md-12 bottom20 bg_blue_bold">
                <p class="text-center"><i class="glyphicon glyphicon-map-marker"></i> <b>GEOLOCALIZAÇÃO DE OBRAS</b></p>
            </div>
            <?php
                $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
                if($get == "return"){
                    echo "<div class='col-md-12 bg_red'><i class='glyphicon glyphicon-alert'></i> Por favor Selecione uma Cidade</div>";
                }
            ?>
            <div class="col-md-12 jumbotron bottom20 parallax5">
                <div class="col-md-6">
                    <form class="search" action="search-maps.php" method="post">
                        <label class="control-label"><i class="glyphicon glyphicon-map-marker"></i> Estado</label>
                        <select name="Estado" class="form-control" id="estado">
                            <option value="0">SELECIONE O ESTADO</option>
                            <?php
                                require_once "conexao.php";
                                $pdo  = conectar();
                                $read = $pdo->prepare("SELECT *FROM tb_estados_est ORDER BY IdEstado");
                                $read->execute();

                                foreach($read->fetchAll(PDO::FETCH_ASSOC) AS $est):
                                    extract($est);
                                    echo "<option value='$UF'>$UF</option>";
                                endforeach;
                            ?>
                        </select>
                        <div class="help-block with-errors"></div>
                        <label class="control-label"><i class="glyphicon glyphicon-map-marker"></i> Cidade</label>
                        <select name="Cidade" class="form-control" aria-describedby="inputGroup-sizing-lg" id="cidade">
                            <option value=""> - SELECIONE PRIMEIRO O ESTADO</option>
                        </select>
                        <div class="help-block with-errors"></div>
                        <button type="submit" class="btn btn-success btn-lg btn-block submit" value="1" title="Pesquisar" name="submit"><i class="glyphicon glyphicon-search"></i> Pesquisar</button> 
                    </form>
                </div>
            </div>
        </div>
        <?php include "https://www.intecbrasil.com.br/Intec-interna/admin/inc/footer.html";?>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script> 
        <script>
            $(function(){      
                $('#estado').on('change', function() {
                    $.ajax({
                        type: 'POST',
                        url: 'json/cidade.php',
                        dataType: 'html',
                        data: {'Estado': $('#estado').val()},
                        // Antes de carregar os registros, mostra para o usuário que está
                        // sendo carregado.
                        beforeSend: function(xhr) {
                            $('#cidade').attr('disabled', 'disabled');
                            $('#cidade').html('<option value="">Carregando...</option>');
                        },
                        // Após carregar, coloca a lista dentro do select de cidades.
                        success: function(data) {
                            if ($('#estado').val() !== '') {
                                // Adiciona o retorno no campo, habilita e da foco
                                $('#cidade').html('<option value="">Selecione agora a Cidade para a pesquisa</option>');
                                $('#cidade').append(data);
                                $('#cidade').removeAttr('disabled').focus();
                            } else {
                                $('#cidade').html('<option value="">Selecione um estado</option>');
                                $('#cidade').attr('disabled', 'disabled');
                            }
                        }
                    });
                    $("form.search").on("submit", function(){
                    //Window.setTimeout(function(){
                    $(".submit").html("Aguarde, fazendo pesquisa... <img src='images/load.gif'/>");
                    //}, 3000); 
                }); 
                });
            });//Seletor Jquery
        </script>
    </body>
</html>