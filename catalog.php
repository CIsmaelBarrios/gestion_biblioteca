<!DOCTYPE html>
<html lang="es">
<head>
    <title>Catálogo</title>
    <?php
        session_start();
        $LinksRoute="./";
        include './inc/links.php'; 
    ?>
    <script type="text/javascript" src="js/jPages.js"></script>
    <script>
        $(document).ready(function(){
            $(function(){
              $("div.holder").jPages({
                containerID : "itemContainer",
                perPage: 20
              });
            });
            $(document).on('click', '.btn-update', function(){
                var bookCode = $(this).attr('data-code');
                var url = $(this).attr('data-url');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: 'code='+bookCode,
                    success: function(data){
                        $('#ModalData').html(data);
                        $('#ModalUpdate').modal({
                            show: true,
                            backdrop: "static"
                        });
                    }
                });
                return false;
            });
            $(document).on('click', '.btn-delete', function(){
                var bookCode = $(this).attr('data-code');
                var url = $(this).attr('data-url');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: 'code='+bookCode,
                    success: function(data){
                        location.reload();
                    }
                });
                return false;
            });
        });
    </script>
</head>
<body>
    <?php 
        include './library/configServer.php';
        include './library/consulSQL.php';
        include './process/SecurityUser.php';
        include './inc/NavLateral.php';
    ?>
    <div class="content-page-container full-reset custom-scroll-containers">
        <?php 
            include './inc/NavUserInfo.php';  
        ?>
        <div class="container">
            <div class="page-header">
              <h1 class="all-tittles">Sistema bibliotecario <small>Catálogo de libros</small></h1>
            </div>
        </div>
        <div class="container-fluid" style="margin: 50px 0;">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <img src="assets/img/checklist.png" alt="pdf" class="img-responsive center-box" style="max-width: 110px;">
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 text-justify lead">
                    Bienvenido al catálogo, puedes buscar libros por título. Puedes actualizar o eliminar los datos del libro.
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <h2 class="text-center all-tittles">Listado de libros</h2>
            <?php
                $checkingAllBooks = ejecutarSQL::consultar("SELECT * FROM libro");
                if (mysqli_num_rows($checkingAllBooks) > 0) {
                    echo '<div class="table-responsive">
                        <div class="div-table" style="margin:0 !important;">
                            <div class="div-table-row div-table-row-list" style="background-color:#DFF0D8; font-weight:bold;">
                                <div class="div-table-cell" style="width: 6%;">#</div>
                                <div class="div-table-cell" style="width: 20%;">Título</div>
                                <div class="div-table-cell" style="width: 20%;">Autor</div>
                                <div class="div-table-cell" style="width: 15%;">Año</div>
                                <div class="div-table-cell" style="width: 9%;">Actualizar</div>
                                <div class="div-table-cell" style="width: 9%;">Eliminar</div>
                            </div>
                        </div>
                    </div>';
                    echo '<ul id="itemContainer" class="list-unstyled">';
                    $c = 1;
                    while ($dataBook = mysqli_fetch_array($checkingAllBooks, MYSQLI_ASSOC)) {
                        echo '<li>
                            <div class="table-responsive">
                                <div class="div-table" style="margin:0 !important;">
                                    <div class="div-table-row div-table-row-list">
                                        <div class="div-table-cell" style="width: 6%;">'.$c.'</div>
                                        <div class="div-table-cell" style="width: 20%;">'.$dataBook['Titulo'].'</div>
                                        <div class="div-table-cell" style="width: 20%;">'.$dataBook['Autor'].'</div>
                                        <div class="div-table-cell" style="width: 15%;">'.$dataBook['Year'].'</div>
                                        <div class="div-table-cell" style="width: 9%;"><button class="btn btn-success btn-update" data-code="'.$dataBook['CodigoLibro'].'" data-url="process/SelectDataBook.php"><i class="zmdi zmdi-refresh"></i></button></div>
                                        <div class="div-table-cell" style="width: 9%;"><button class="btn btn-danger btn-delete" data-code="'.$dataBook['CodigoLibro'].'" data-url="process/DeleteBook.php"><i class="zmdi zmdi-delete"></i></button></div>
                                    </div>
                                </div>
                            </div>
                        </li>';
                        $c++;
                    }
                    echo '</ul><div class="holder"></div>';
                } else {
                    echo '<br><br><br><h3 class="text-center all-tittles">No hay libros registrados en el sistema</h3><br><br><br>';
                }
                mysqli_free_result($checkingAllBooks);
            ?>
        </div>
        <div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form class="form_SRCB modal-content" action="process/UpdateBook.php" method="post" data-type-form="update" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-center all-tittles">Actualizar datos del libro</h4>
                    </div>
                    <div class="modal-body" id="ModalData"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="zmdi zmdi-refresh"></i> &nbsp;&nbsp; Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="ModalHelp">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-center all-tittles">Ayuda del sistema</h4>
                    </div>
                    <div class="modal-body">
                        <?php include './help/help-catalog.php'; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="zmdi zmdi-thumb-up"></i> &nbsp; De acuerdo</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include './inc/footer.php'; ?>
    </div>
</body>
</html>
                