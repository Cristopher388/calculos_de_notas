<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Calcular Notas</h1>

</div>

<!-- Content Row -->

<div class="row">

    <div class="col-12">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Calcular notas en formato JSON</h6>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3 col-12">
                        <label for="textarea">Inserte una cadena de texto formada por número separados por comas</label>
                        <textarea class="form-control" id="data" name="data"
                                  rows="3"><?php echo $data['data'] ?? ''; ?></textarea>
                        <?php if (isset($data['errors'])){ ?>
                            <?php foreach ($data['errors'] as $error){ ?>
                                <p class="text-danger small"><?php echo $error ?? ''; ?></p>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <input type="submit" value="Enviar" name="enviar2" class="btn btn-primary">
                    </div>
                </form>
                <?php if(isset($data['informacion_clase'])){ ?>
                    <table id="miTabla" class="table table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Media</th>
                                <th>Aprobados</th>
                                <th>Suspensos</th>
                                <th>Máximo</th>
                                <th>Mínimo</th>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($data['informacion_clase'] as $asignaturas => $info){
                                    echo "<tr>";
                                    echo "<td>".$asignaturas."</td>";
                                    echo "<td>".$info['media']."</td>";
                                    echo "<td>".$info['aprobados']."</td>";
                                    echo "<td>".$info['suspensos']."</td>";
                                    echo "<td>";
                                        foreach ($info['max']['alumnos']as $alumnos){
                                            echo "<p>".$alumnos.": ".$info['max']['nota']."</p>";
                                        }
                                    echo "</td>";
                                    echo "<td>";
                                        foreach ($info['min']['alumnos']as $alumnos){
                                            echo "<p>".$alumnos.": ".$info['min']['nota']."</p>";
                                        }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>                        
</div>