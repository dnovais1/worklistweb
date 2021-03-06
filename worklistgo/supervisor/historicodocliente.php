<?php
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}
?>

<!DOCTYPE html>
<html lang="pt">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="O Worklist é um sistema de monitoramento de indicadores de vendas." content="">
        <meta name="Worknet" content="Worknet">
        <link rel="icon" href="img/favicon.png"> <!--Coloca Icone da aba do navegador-->
        <title>Histórico do Cliente</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->

        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/carteira.css" rel="stylesheet">
        <script src="js/jquery-3.2.1.js"></script>


        <script type="text/javascript">

            $(document).ready(function () {
                $('#btn_atualizar').click(function () {

                    //limpar grafico antes de atualizar
                    $('#myBarChartHistoricoCliente').remove();
                    $('#graficopositivacao').append('<canvas id="myBarChartHistoricoCliente" width="100" height="50"></canvas>');

                    // $('#graficoinadimplencia').remove();
                    // $('#graficoinad').append('<canvas id="graficoinadimplencia" width="100" height="50"></canvas>');

                    //chamar funções de execuçã das querys
                    atualizaIndicadorHistorico();
                    atualizaSellout();
                    atualizagraficoIndimplencia();
                });

                function atualizaSellout() {//ajustado para a nova estrutura de supervisao
                    var iddocliente = $("#selectCliente").val();
                    var rca = $(".select_rca").val();
                    $.post('classes/sql_sellout_cliente.php', {idcliente: iddocliente, rca: rca}, function (data) {
                        $("#selloutatualiza").empty();
                        $("#selloutatualiza").html(data);
                    })
                }

                function atualizaIndicadorHistorico() {//ajustado para a nova estrutura de supervisao
                    var iddocliente = $("#selectCliente").val();
                    var rca = $(".select_rca").val();
                    $.post('classes/sql_sellout_cliente_geral.php', {idcliente: iddocliente, rca: rca}, function (data) {
                        $("#preencher").empty();
                        $("#preencher").html(data);

                    })
                }

                function atualizagraficoIndimplencia() {//ajustado para a nova estrutura de supervisao
                    var iddocliente = $("#selectCliente").val();
                    var rca = $(".select_rca").val();
                    $.post('classes/sql_inadimplencia_historico.php', {idcliente: iddocliente, rca: rca}, function (data3) {
                        $("#graficoinad").empty();
                        $("#graficoinad").html(data3);
                    })
                }
                $("#inputpreencher").change(function () {
                    var index = $(this).parent().index();
                    var nth = "#dataTable td:nth-child(" + (index + 2).toString() + ")";
                    var valor = $(this).val().toUpperCase();
                    $("#dataTable tbody tr").show();
                    $(nth).each(function () {
                        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                            $(this).parent().hide();
                        }
                    });
                });


                $("#pesquisaindustria").keyup(function () {
                    var index = $(this).parent().index();
                    var nth = "#dataTable td:nth-child(" + (index + 1).toString() + ")";
                    var valor = $(this).val().toUpperCase();
                    $("#dataTable tbody tr").show();
                    $(nth).each(function () {
                        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                            $(this).parent().hide();
                        }
                    });
                });

                $("#pesquisaindustria").blur(function () {
                    $(this).val("");
                });

                $('.js-example-responsive').select2();

                function salvaPlanilha1() {
                    var htmlPlanilha = "<table>" + document.getElementById("historicorca").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar1').click(function () {
                    salvaPlanilha1();
                });
                
                function salvaPlanilha2() {
                    var htmlPlanilha = "<table>" + document.getElementById("historicoindus").innerHTML + "</table>";
                    var htmlBase64 = btoa(htmlPlanilha);
                    var link = "data:application/vnd.ms-excel;base64," + htmlBase64;

                    window.open(link);
                }

                $('#exportar2').click(function () {
                    salvaPlanilha2();
                });


            })
        </script>
    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <?PHP
        include_once 'menu.php';
        ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="container"><!-- Inicio do divisão framework -->
                    <div class="row"> <!-- Inicio row framework -->
                        <div class="col-xl-7 col-lg-9 col-md-12 col-sm-12 "> <!-- Inicio coluna framework -->

                            <!-- Example DataTables Card-->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-filter"></i> Filtro de Clientes
                                </div>


                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <span class="legenda_indus"></span>
                                        <select class="js-example-responsive select_rca">
                                            <option value="1">TODOS OS RCAS</option>
                                            <?php
                                            require_once 'classes/bd.php';
                                            $bd = new bd();
                                            $queryvendedor = "SELECT distinct 
a.idvendedor,
a.apelido
FROM 
sys_vendedores a,
sys_supervisao c
where 
a.idvendedor=c.idrca and
c.idsupervisor = '" . $_SESSION['idrepresentante']."'
ORDER BY a.apelido";
                                            $resultvendedor = pg_query($queryvendedor);

                                            while ($rowvendedor = pg_fetch_array($resultvendedor)) {
                                                echo "<option value ='" . $rowvendedor['idvendedor'] . "'>" . $rowvendedor['apelido'] . "</option>";
                                            }
                                            ?>
                                        </select>                       
                                        <i class="fa fa-refresh" id="btn_atualizar"></i>
                                    </div>
                                </div>


                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <span class="legenda_indus"> </span>

                                        <select class="js-example-responsive" id="selectCliente">
                                            <option value="1">TODOS OS CLIENTES</option>
                                            <?php
                                            require_once 'classes/bd.php';
                                            $bd = new bd();
                                            $queryindustrias = " select distinct
idcli,
razao_social
from 
sys_clientes a,
sys_carteira b,
sys_acessos c
where
a.idcli = b.idcliente and
c.idrepresentante = b.idvendedor and
c.idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ".
" ORDER BY razao_social ";

                                            $resultindustrias = pg_query($queryindustrias);

                                            while ($rowindustrias = pg_fetch_array($resultindustrias)) {
                                                echo "<option value ='" . $rowindustrias['idcli'] . "'>" . $rowindustrias['razao_social'] . "</option>";
                                            }
                                            ?>
                                        </select>




                                    </div>
                                </div>
                                <div class="card-footer small text-muted">

                                </div>
                            </div>


                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Histórico de Sell-Out Mensal
                                    <i class="fa fa-file-excel-o" id="exportar1"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <table class="table table-bordered dataTable no-footer" id="historicorca" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador" >
                                                    <th align="center">RCA</th>
                                                    <th align="center"><?php echo $_SESSION['MES5'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES4'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES3'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES2'] ?></th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                    <th align="center">MÉDIA</th>
                                                </tr>
                                            </thead>

                                            <tbody id="preencher">

                                            </tbody>                               
                                        </table>

                                    </div>
                                    <div class="card-body" id="graficopositivacao">
                                        <canvas id="myBarChartHistoricoCliente" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Histórico  por Indústria
                                    <i class="fa fa-file-excel-o" id="exportar2"></i>
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <input type="text" id="pesquisaindustria" placeholder="Pesquisar Indústria" style="width: 70%;height: 25px;">
                                        <select id="inputpreencher" style="width: 29%; margin-left:1%;">
                                            <option value="">Todos</option>
                                            <option value="3">Ouro</option>
                                            <option value="2">Prata</option>
                                            <option value="1">Bronze</option>
                                            <option value="0">Black</option>
                                        </select>


                                        <table class="table table-bordered dataTable no-footer" id="historicoindus" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="cabecalho_indicador">
                                                    <!--<th>Codigo</th>-->
                                                    <th align="center">INDÚSTRIA</th>
                                                    <th align="center">FQ&nbsp;</th>
                                                    <th align="center">MÉDIA</th>
                                                    <th align="center"><?php echo $_SESSION['MES1'] ?></th>
                                                    <th align="center">DETALHAR</th>
                                                   <!-- <th>&nbspST</th>-->
                                                </tr>   
                                            </thead>
                                            <tbody id="selloutatualiza">                    

                                            </tbody>                               
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <i class="fa fa-calendar"></i> Gráfico de Inadimplência
                                </div>
                                <div class="card-body">
                                    <div  class="table-responsive dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    </div>
                                    <div class="card-body" id="graficoinad">
                                        <canvas id="graficoinadimplencia" width="100" height="50"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Atualizado Hoje</div>
                            </div>


                            <div class="modal" id="janela">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header card-header">

                                            <i class="fa fa-calendar"></i> Histórico venda Produtos
                                            


                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>

                                        </div>
                                        <div class="modal-body" id="vendaproduto">

                                        </div>
                                        <div class="card-footer small text-muted">Atualizado Hoje
                                        <i class="fa fa-file-excel-o" id="exportar3"></i>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div><!-- Inicio coluna framework -->
                    </div><!-- Inicio row framework -->
                </div><!-- Inicio divisao framework -->
            </div>

            <footer class="sticky-footer" id="mainNav">
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © WorklistWeb - 2017</small>
                    </div>
                </div>
            </footer>
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Logout Modal-->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Deseja Sair?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Selecione "Sair" se você estiver pronto para terminar sua sessão atual.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <a class="btn btn-primary" href="../login.php">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bootstrap core JavaScript-->

            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
            <!-- Page level plugin JavaScript-->
            <!--<script src="vendor/chart.js/Chart.min.js"></script>-->
            <script src="vendor/chart.js/Chart.min.js"></script>
            <script src="vendor/datatables/jquery.dataTables.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin.min.js"></script>
            <!-- Custom scripts for this page-->
            <script src="js/sbs-admin-datatables.min.js"></script>
            <link href="css/selec2.min.css" rel="stylesheet" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> 
        </div>
    </body>

</html>
